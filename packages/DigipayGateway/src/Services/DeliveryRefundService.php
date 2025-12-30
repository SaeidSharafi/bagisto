<?php

declare(strict_types=1);

namespace DigipayGateway\Services;

use DigipayGateway\Contracts\PaymentGatewayInterface;
use DigipayGateway\DataTransferObjects\DeliverRequest;
use DigipayGateway\DataTransferObjects\DeliverResponse;
use DigipayGateway\DataTransferObjects\RefundRequest;
use DigipayGateway\DataTransferObjects\RefundResponse;
use DigipayGateway\DataTransferObjects\RefundInquiryResponse;
use DigipayGateway\Exceptions\DeliverException;
use DigipayGateway\Exceptions\RefundException;
use Illuminate\Support\Facades\Log;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Repositories\OrderTransactionRepository;

/**
 * Service for handling order delivery and refund operations with Digipay.
 */
class DeliveryRefundService
{
    // Payment types that support delivery confirmation
    private const DELIVERY_SUPPORTED_TYPES = [
        5,  // CREDIT
        13, // BNPL
    ];

    // Payment types that support refund
    private const REFUND_SUPPORTED_TYPES = [
        0,  // IPG
        11, // WALLET
        5,  // CREDIT
        13, // BNPL
        24, // CREDIT-CARD
    ];

    private PaymentGatewayInterface $gateway;
    private OrderRepository $orderRepository;
    private OrderTransactionRepository $orderTransactionRepository;

    public function __construct(
        PaymentGatewayInterface $gateway,
        OrderRepository $orderRepository,
        OrderTransactionRepository $orderTransactionRepository
    ) {
        $this->gateway = $gateway;
        $this->orderRepository = $orderRepository;
        $this->orderTransactionRepository = $orderTransactionRepository;
    }

    /**
     * Confirm delivery of an order.
     * Only for CREDIT (type=5) and BNPL (type=13) payments.
     *
     * @param Order $order
     * @return DeliverResponse
     * @throws DeliverException
     */
    public function confirmDelivery(Order $order): DeliverResponse
    {
        Log::channel($this->getLogChannel())->info('[Digipay] Confirming delivery', [
            'order_id' => $order->id,
        ]);

        // Validate order status
        if (!in_array($order->status, ['completed', 'processing'])) {
            throw DeliverException::invalidOrderStatus($order->id, $order->status);
        }

        // Get payment data from transaction
        $paymentData = $this->getDigipayPaymentData($order);

        if (!$paymentData || empty($paymentData['tracking_code'])) {
            throw DeliverException::missingTrackingCode($order->id);
        }

        $type = (int) ($paymentData['gateway_type'] ?? 0);

        // Check if payment type supports delivery
        if (!in_array($type, self::DELIVERY_SUPPORTED_TYPES)) {
            throw DeliverException::unsupportedPaymentType($type);
        }

        // Create deliver request
        $deliverRequest = DeliverRequest::fromOrder(
            $order,
            $paymentData['tracking_code'],
            $type
        );

        Log::channel($this->getLogChannel())->info('[Digipay] Delivery request prepared', [
            'order_id' => $order->id,
            'tracking_code' => $paymentData['tracking_code'],
            'type' => $type,
            'request_data' => $deliverRequest->toArray(),
        ]);

        $response = $this->gateway->deliver($deliverRequest);

        Log::channel($this->getLogChannel())->info('[Digipay] Delivery confirmed', [
            'order_id' => $order->id,
            'message' => $response->getMessage(),
        ]);

        // Update order with delivery confirmation
        $this->updateOrderDeliveryStatus($order, $paymentData);

        return $response;
    }

    /**
     * Refund a payment.
     *
     * @param Order $order
     * @param int|null $amount Amount to refund. If null, full refund.
     * @return RefundResponse
     * @throws RefundException
     */
    public function refund(Order $order, ?int $amount = null): RefundResponse
    {
        Log::channel($this->getLogChannel())->info('[Digipay] Processing refund', [
            'order_id' => $order->id,
            'amount' => $amount,
        ]);

        // Validate order status - should be completed or processing
        if (!in_array($order->status, ['completed', 'processing', 'closed'])) {
            throw RefundException::invalidOrderStatus($order->id, $order->status);
        }

        // Get payment data from transaction
        $paymentData = $this->getDigipayPaymentData($order);

        if (!$paymentData || empty($paymentData['tracking_code'])) {
            throw RefundException::missingTrackingCode($order->id);
        }

        // Check if already refunded
        if ($this->isOrderRefunded($order)) {
            throw RefundException::alreadyRefunded($order->id);
        }

        $type = (int) ($paymentData['gateway_type'] ?? 0);

        // Determine refund amount
        $refundAmount = $amount ?? (int) $order->grand_total;

        // Validate amount doesn't exceed order total
        if ($refundAmount > (int) $order->grand_total) {
            throw RefundException::amountExceedsTotal(
                $order->id,
                $refundAmount,
                (int) $order->grand_total
            );
        }

        // Create refund request
        if ($amount !== null) {
            $refundRequest = RefundRequest::partialRefund(
                $order,
                $refundAmount,
                $paymentData['tracking_code'],
                $type
            );
        } else {
            $refundRequest = RefundRequest::fromOrder(
                $order,
                $paymentData['tracking_code'],
                $type
            );
        }

        Log::channel($this->getLogChannel())->info('[Digipay] Refund request prepared', [
            'order_id' => $order->id,
            'refund_provider_id' => $refundRequest->getProviderId(),
            'amount' => $refundAmount,
            'sale_tracking_code' => $paymentData['tracking_code'],
            'type' => $type,
        ]);

        $response = $this->gateway->refund($refundRequest);

        Log::channel($this->getLogChannel())->info('[Digipay] Refund successful', [
            'order_id' => $order->id,
            'refund_tracking_code' => $response->getTrackingCode(),
            'message' => $response->getMessage(),
        ]);

        // Update order with refund info
        $this->updateOrderRefundStatus($order, $refundRequest, $response);

        return $response;
    }

    /**
     * Check refund status.
     *
     * @param string $refundProviderId
     * @param int $type
     * @return RefundInquiryResponse
     * @throws RefundException
     */
    public function inquireRefund(string $refundProviderId, int $type): RefundInquiryResponse
    {
        Log::channel($this->getLogChannel())->info('[Digipay] Inquiring refund status', [
            'refund_provider_id' => $refundProviderId,
            'type' => $type,
        ]);

        $response = $this->gateway->inquireRefund($refundProviderId, $type);

        Log::channel($this->getLogChannel())->info('[Digipay] Refund inquiry result', [
            'refund_provider_id' => $refundProviderId,
            'status' => $response->getStatus(),
            'result_code' => $response->getResultCode(),
        ]);

        return $response;
    }

    /**
     * Check if an order is paid via Digipay.
     *
     * @param Order $order
     * @return bool
     */
    public function isDigipayOrder(Order $order): bool
    {
        return $order->payment?->method === 'digipay';
    }

    /**
     * Check if delivery confirmation is required for this order.
     *
     * @param Order $order
     * @return bool
     */
    public function requiresDeliveryConfirmation(Order $order): bool
    {
        if (!$this->isDigipayOrder($order)) {
            return false;
        }

        $paymentData = $this->getDigipayPaymentData($order);
        if (!$paymentData) {
            return false;
        }

        $type = (int) ($paymentData['gateway_type'] ?? 0);

        return in_array($type, self::DELIVERY_SUPPORTED_TYPES);
    }

    /**
     * Get Digipay payment data from order transaction.
     *
     * @param Order $order
     * @return array|null
     */
    public function getDigipayPaymentData(Order $order): ?array
    {
        // First try to get from order transactions
        $transaction = $order->transactions()
            ->where('payment_method', 'digipay')
            ->first();

        if ($transaction && $transaction->data) {
            $data = is_string($transaction->data)
                ? json_decode($transaction->data, true)
                : $transaction->data;

            if (is_array($data)) {
                return $data;
            }
        }

        // Fallback to payment_data on order
        if ($order->payment_data) {
            $data = is_string($order->payment_data)
                ? json_decode($order->payment_data, true)
                : $order->payment_data;

            if (is_array($data)) {
                return $data;
            }
        }

        return null;
    }

    /**
     * Check if order has already been refunded.
     *
     * @param Order $order
     * @return bool
     */
    private function isOrderRefunded(Order $order): bool
    {
        $paymentData = $this->getDigipayPaymentData($order);

        return isset($paymentData['refund_tracking_code']);
    }

    /**
     * Update order with delivery confirmation status.
     *
     * @param Order $order
     * @param array $paymentData
     * @return void
     */
    private function updateOrderDeliveryStatus(Order $order, array $paymentData): void
    {
        $paymentData['delivery_confirmed'] = true;
        $paymentData['delivery_confirmed_at'] = now()->toIso8601String();

        $this->updateOrderPaymentData($order, $paymentData);
    }

    /**
     * Update order with refund information.
     *
     * @param Order $order
     * @param RefundRequest $request
     * @param RefundResponse $response
     * @return void
     */
    private function updateOrderRefundStatus(Order $order, RefundRequest $request, RefundResponse $response): void
    {
        $paymentData = $this->getDigipayPaymentData($order) ?? [];

        $paymentData['refund_provider_id'] = $request->getProviderId();
        $paymentData['refund_tracking_code'] = $response->getTrackingCode();
        $paymentData['refund_amount'] = $request->getAmount();
        $paymentData['refunded_at'] = now()->toIso8601String();

        $this->updateOrderPaymentData($order, $paymentData);

        // Update order status to closed/refunded
        $this->orderRepository->updateOrderStatus($order, 'closed');
    }

    /**
     * Update order payment data.
     *
     * @param Order $order
     * @param array $paymentData
     * @return void
     */
    private function updateOrderPaymentData(Order $order, array $paymentData): void
    {
        // Update transaction data
        $transaction = $order->transactions()
            ->where('payment_method', 'digipay')
            ->first();

        if ($transaction) {
            $transaction->update([
                'data' => json_encode($paymentData),
            ]);
        }

        // Also update order payment_data
        $this->orderRepository->update([
            'payment_data' => json_encode($paymentData),
        ], $order->id);
    }

    /**
     * Get log channel.
     *
     * @return string
     */
    private function getLogChannel(): string
    {
        return config('digipay.logging.channel', 'stack');
    }
}
