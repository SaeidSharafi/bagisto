<?php

declare(strict_types=1);

namespace DigipayGateway\Services;

use DigipayGateway\Contracts\PaymentGatewayInterface;
use DigipayGateway\Infrastructure\ConfigRepository;
use DigipayGateway\DataTransferObjects\TicketRequest;
use DigipayGateway\DataTransferObjects\TicketResponse;
use DigipayGateway\DataTransferObjects\VerifyRequest;
use DigipayGateway\DataTransferObjects\VerifyResponse;
use DigipayGateway\DataTransferObjects\CallbackPayload;
use DigipayGateway\Exceptions\DigipayException;
use DigipayGateway\Exceptions\TokenException;
use DigipayGateway\Exceptions\VerificationException;
use DigipayGateway\Exceptions\OrderNotFoundException;
use Illuminate\Support\Facades\Log;
use Webkul\Checkout\Facades\Cart;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Models\Order;

/**
 * Orchestrates the payment flow: ticket creation, verification, order processing.
 */
class PaymentOrchestrator
{
    private PaymentGatewayInterface $gateway;
    private ConfigRepository $config;
    private OrderRepository $orderRepository;
    private OrderProcessor $orderProcessor;

    public function __construct(
        PaymentGatewayInterface $gateway,
        ConfigRepository $config,
        OrderRepository $orderRepository,
        OrderProcessor $orderProcessor
    ) {
        $this->gateway = $gateway;
        $this->config = $config;
        $this->orderRepository = $orderRepository;
        $this->orderProcessor = $orderProcessor;
    }

    /**
     * Initiate payment: create order and get redirect URL.
     *
     * @return array{redirect_url: string, order_id: int, provider_id: string}
     * @throws TokenException
     * @throws DigipayException
     */
    public function initiatePayment(): array
    {
        // Create order from cart
        $order = $this->orderRepository->create(Cart::prepareDataForOrder());

        Log::channel($this->getLogChannel())->info('[Digipay] Order created', [
            'order_id' => $order->id,
            'grand_total' => $order->grand_total,
        ]);

        try {
            // Generate unique provider ID
            $providerId = $this->generateProviderId($order->id);

            // Build ticket request
            $ticketRequest = new TicketRequest(
                (int) $order->grand_total,
                $this->getCustomerPhone($order),
                $providerId,
                $this->config->getCallbackUrl(),
                $this->buildDescription($order)
            );

            Log::channel($this->getLogChannel())->info('[Digipay] Ticket request prepared', [
                'order_id' => $order->id,
                'request_data' => $ticketRequest->toArray(),
            ]);

            // Create ticket
            $ticketResponse = $this->gateway->createTicket($ticketRequest);

            Log::channel($this->getLogChannel())->info('[Digipay] Ticket created', [
                'order_id' => $order->id,
                'provider_id' => $providerId,
                'ticket' => $ticketResponse->getTicket(),
                'redirect_url' => $ticketResponse->getRedirectUrl(),
            ]);

            // Store provider ID in order for later verification
            $this->storeProviderIdOnOrder($order, $providerId, $ticketResponse->getTicket());

            return [
                'redirect_url' => $ticketResponse->getRedirectUrl(),
                'order_id' => $order->id,
                'provider_id' => $providerId,
            ];
        } catch (DigipayException $e) {
            // Cancel order if ticket creation fails
            $this->orderRepository->cancel($order->id);

            Log::channel($this->getLogChannel())->error('[Digipay] Payment initiation failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
                'error_code' => $e->getErrorCode(),
                'context' => $e->getContext(),
            ]);

            throw $e;
        }
    }

    /**
     * Handle callback from Digipay after payment.
     *
     * @param CallbackPayload $payload
     * @return array{success: bool, order_id: int, order: Order|null, message: string}
     * @throws DigipayException
     */
    public function handleCallback(CallbackPayload $payload): array
    {
        Log::channel($this->getLogChannel())->info('[Digipay] Callback received', [
            'provider_id' => $payload->getProviderId(),
            'tracking_code' => $payload->getTrackingCode(),
            'result' => $payload->getResult(),
            'amount' => $payload->getAmount(),
        ]);

        // Find order by provider ID
        $order = $this->findOrderByProviderId($payload->getProviderId());

        if (!$order) {
            throw OrderNotFoundException::byProviderId($payload->getProviderId());
        }

        // Check if order is already processed
        if (in_array($order->status, ['processing', 'completed', 'closed'])) {
            return [
                'success' => true,
                'order_id' => $order->id,
                'order' => $order,
                'message' => __('digipay::messages.order_already_processed'),
            ];
        }

        // If payment failed, cancel order
        if (!$payload->isSuccessful()) {
            $this->orderProcessor->cancelOrder($order, __('digipay::messages.payment_failed'));

            return [
                'success' => false,
                'order_id' => $order->id,
                'order' => $order,
                'message' => __('digipay::messages.payment_failed'),
            ];
        }

        // Validate amount
        if ((int) $order->grand_total !== $payload->getAmount()) {
            Log::channel($this->getLogChannel())->warning('[Digipay] Amount mismatch', [
                'order_id' => $order->id,
                'expected' => (int) $order->grand_total,
                'received' => $payload->getAmount(),
            ]);

            $this->orderProcessor->cancelOrder($order, __('digipay::messages.amount_mismatch'));

            throw VerificationException::amountMismatch(
                (int) $order->grand_total,
                $payload->getAmount(),
                $payload->getProviderId()
            );
        }

        try {
            // Verify payment with Digipay
            $verifyRequest = new VerifyRequest(
                $payload->getTrackingCode(),
                $payload->getProviderId(),
                $payload->getType() // Use the type from callback (0 for IPG)
            );

            $verifyResponse = $this->gateway->verify($verifyRequest);

            Log::channel($this->getLogChannel())->info('[Digipay] Payment verified', [
                'order_id' => $order->id,
                'tracking_code' => $verifyResponse->getTrackingCode(),
                'rrn' => $verifyResponse->getRrn(),
            ]);

            // Process successful order
            $this->orderProcessor->processSuccessfulPayment($order, $verifyResponse, $payload);

            return [
                'success' => true,
                'order_id' => $order->id,
                'order' => $order,
                'message' => __('digipay::messages.payment_successful'),
            ];
        } catch (VerificationException $e) {
            Log::channel($this->getLogChannel())->error('[Digipay] Verification failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
                'error_code' => $e->getErrorCode(),
            ]);

            $this->orderProcessor->cancelOrder($order, $e->getUserMessage());

            throw $e;
        }
    }

    /**
     * Generate unique provider ID for order.
     *
     * @param int $orderId
     * @return string
     */
    private function generateProviderId(int $orderId): string
    {
        return 'ORD-' . $orderId . '-' . time();
    }

    /**
     * Extract order ID from provider ID.
     *
     * @param string $providerId
     * @return int|null
     */
    public function extractOrderIdFromProviderId(string $providerId): ?int
    {
        if (preg_match('/^ORD-(\d+)-/', $providerId, $matches)) {
            return (int) $matches[1];
        }

        return null;
    }

    /**
     * Find order by provider ID.
     *
     * @param string $providerId
     * @return \Webkul\Sales\Contracts\Order|null
     */
    private function findOrderByProviderId(string $providerId)
    {
        $orderId = $this->extractOrderIdFromProviderId($providerId);

        if ($orderId) {
            return $this->orderRepository->find($orderId);
        }

        return null;
    }

    /**
     * Get customer phone number.
     *
     * @param \Webkul\Sales\Contracts\Order $order
     * @return string
     */
    private function getCustomerPhone($order): string
    {
        // Try customer's phone first (most reliable)
        if ($order->customer && $order->customer->phone) {
            return $this->normalizePhoneNumber($order->customer->phone);
        }

        // Try billing address phone
        if ($order->billing_address && $order->billing_address->phone) {
            return $this->normalizePhoneNumber($order->billing_address->phone);
        }

        // Try shipping address phone
        if ($order->shipping_address && $order->shipping_address->phone) {
            return $this->normalizePhoneNumber($order->shipping_address->phone);
        }

        // Return empty if no phone found (Digipay may reject this)
        return '';
    }

    /**
     * Normalize phone number to expected format.
     *
     * @param string $phone
     * @return string
     */
    private function normalizePhoneNumber(string $phone): string
    {
        // Remove non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Ensure it starts with 09 for Iranian mobile numbers
        if (strlen($phone) === 10 && substr($phone, 0, 1) === '9') {
            $phone = '0' . $phone;
        }

        return $phone;
    }

    /**
     * Build payment description.
     *
     * @param \Webkul\Sales\Contracts\Order $order
     * @return string
     */
    private function buildDescription($order): string
    {
        return sprintf(
            'پرداخت سفارش شماره %d',
            $order->id
        );
    }

    /**
     * Store provider ID on order for later lookup.
     *
     * @param Order $order
     * @param string $providerId
     * @param string $ticket
     * @return void
     */
    private function storeProviderIdOnOrder($order, string $providerId, string $ticket): void
    {
        // Store in order's additional data using repository
        // The provider ID is already embedded in the order ID, so this is optional
        // but useful for debugging and transaction tracking
        $this->orderRepository->update([
            'payment_data' => json_encode([
                'digipay_provider_id' => $providerId,
                'digipay_ticket' => $ticket,
                'created_at' => now()->toIso8601String(),
            ]),
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
