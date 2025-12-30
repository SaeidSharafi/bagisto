<?php

declare(strict_types=1);

namespace DigipayGateway\Services;

use DigipayGateway\DataTransferObjects\VerifyResponse;
use DigipayGateway\DataTransferObjects\CallbackPayload;
use Illuminate\Support\Facades\Log;
use Webkul\Sales\Models\Order;
use Webkul\Sales\Repositories\OrderRepository;
use Webkul\Sales\Repositories\InvoiceRepository;
use Webkul\Sales\Repositories\OrderTransactionRepository;

/**
 * Handles order processing: status updates, invoice creation, transactions.
 */
class OrderProcessor
{
    private OrderRepository $orderRepository;
    private InvoiceRepository $invoiceRepository;
    private OrderTransactionRepository $orderTransactionRepository;

    public function __construct(
        OrderRepository $orderRepository,
        InvoiceRepository $invoiceRepository,
        OrderTransactionRepository $orderTransactionRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->invoiceRepository = $invoiceRepository;
        $this->orderTransactionRepository = $orderTransactionRepository;
    }

    /**
     * Process successful payment: update status, create invoice, record transaction.
     *
     * @param Order $order
     * @param VerifyResponse $verifyResponse
     * @param CallbackPayload $callbackPayload
     * @return void
     */
    public function processSuccessfulPayment(
        Order $order,
        VerifyResponse $verifyResponse,
        CallbackPayload $callbackPayload
    ): void {
        Log::channel($this->getLogChannel())->info('[Digipay] Processing successful payment', [
            'order_id' => $order->id,
        ]);

        // Update order status to processing
        $this->orderRepository->updateOrderStatus($order, 'processing');
        $order->refresh();

        // Create invoice if order can be invoiced
        if ($order->canInvoice()) {
            $invoice = $this->invoiceRepository->create(
                $this->prepareInvoiceData($order)
            );

            // Create transaction record
            $this->orderTransactionRepository->create(
                $this->prepareTransactionData($order, $invoice, $verifyResponse, $callbackPayload)
            );

            Log::channel($this->getLogChannel())->info('[Digipay] Invoice and transaction created', [
                'order_id' => $order->id,
                'invoice_id' => $invoice->id,
            ]);
        }
    }

    /**
     * Cancel order with reason.
     *
     * @param Order $order
     * @param string $reason
     * @return void
     */
    public function cancelOrder(Order $order, string $reason = ''): void
    {
        Log::channel($this->getLogChannel())->info('[Digipay] Cancelling order', [
            'order_id' => $order->id,
            'reason' => $reason,
        ]);

        $this->orderRepository->cancel($order->id);
    }

    /**
     * Prepare invoice data for creation.
     *
     * @param Order $order
     * @return array<string, mixed>
     */
    private function prepareInvoiceData(Order $order): array
    {
        $invoiceData = [
            'order_id' => $order->id,
        ];

        foreach ($order->items as $item) {
            $invoiceData['invoice']['items'][$item->id] = $item->qty_to_invoice;
        }

        return $invoiceData;
    }

    /**
     * Prepare transaction data for creation.
     *
     * @param Order $order
     * @param mixed $invoice
     * @param VerifyResponse $verifyResponse
     * @param CallbackPayload $callbackPayload
     * @return array<string, mixed>
     */
    private function prepareTransactionData(
        Order $order,
        $invoice,
        VerifyResponse $verifyResponse,
        CallbackPayload $callbackPayload
    ): array {
        return [
            'order_id' => $order->id,
            'transaction_id' => $verifyResponse->getTrackingCode(),
            'status' => 'موفق',
            'payment_method' => 'digipay',
            'invoice_id' => $invoice->id,
            'data' => json_encode([
                'tracking_code' => $verifyResponse->getTrackingCode(),
                'provider_id' => $verifyResponse->getProviderId(),
                'rrn' => $verifyResponse->getRrn(),
                'masked_pan' => $verifyResponse->getMaskedPan(),
                'psp_name' => $verifyResponse->getPspName() ?? $callbackPayload->getPspName(),
                'terminal_id' => $verifyResponse->getTerminalId(),
                'payment_gateway' => $verifyResponse->getPaymentGateway(),
                'gateway_type' => $callbackPayload->getType(),
            ]),
            'amount' => $order->grand_total,
        ];
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
