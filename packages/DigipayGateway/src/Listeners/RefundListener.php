<?php

declare(strict_types=1);

namespace DigipayGateway\Listeners;

use DigipayGateway\Services\DeliveryRefundService;
use DigipayGateway\Exceptions\RefundException;
use Illuminate\Support\Facades\Log;
use Webkul\Sales\Contracts\Refund;

/**
 * Listener for refund events to automatically call Digipay refund API.
 */
class RefundListener
{
    private DeliveryRefundService $deliveryRefundService;

    public function __construct(DeliveryRefundService $deliveryRefundService)
    {
        $this->deliveryRefundService = $deliveryRefundService;
    }

    /**
     * Handle refund created event.
     * Automatically calls Digipay refund API when a refund is created in admin panel.
     *
     * @param Refund $refund
     * @return void
     */
    public function onRefundCreated($refund): void
    {
        $order = $refund->order;

        if (!$order) {
            Log::channel($this->getLogChannel())->warning('[Digipay] Refund event received but order not found', [
                'refund_id' => $refund->id,
            ]);
            return;
        }

        // Only process Digipay orders
        if (!$this->deliveryRefundService->isDigipayOrder($order)) {
            Log::channel($this->getLogChannel())->debug('[Digipay] Refund event skipped - not a Digipay order', [
                'order_id' => $order->id,
                'payment_method' => $order->payment?->method ?? 'unknown',
            ]);
            return;
        }

        // Check if already refunded via Digipay
        $paymentData = $this->deliveryRefundService->getDigipayPaymentData($order);
        if (isset($paymentData['refund_tracking_code'])) {
            Log::channel($this->getLogChannel())->info('[Digipay] Order already refunded via Digipay', [
                'order_id' => $order->id,
                'refund_tracking_code' => $paymentData['refund_tracking_code'],
            ]);
            return;
        }

        Log::channel($this->getLogChannel())->info('[Digipay] Processing refund from admin panel', [
            'order_id' => $order->id,
            'refund_id' => $refund->id,
            'refund_amount' => $refund->grand_total,
        ]);

        try {
            // Use the refund amount from Bagisto refund
            $refundAmount = (int) $refund->grand_total;

            $response = $this->deliveryRefundService->refund($order, $refundAmount);

            Log::channel($this->getLogChannel())->info('[Digipay] Automatic refund via Digipay successful', [
                'order_id' => $order->id,
                'refund_id' => $refund->id,
                'digipay_tracking_code' => $response->getTrackingCode(),
            ]);
        } catch (RefundException $e) {
            Log::channel($this->getLogChannel())->error('[Digipay] Automatic refund via Digipay failed', [
                'order_id' => $order->id,
                'refund_id' => $refund->id,
                'error' => $e->getMessage(),
                'error_code' => $e->getErrorCode(),
                'context' => $e->getContext(),
            ]);

            // Don't throw - let the Bagisto refund continue
            // Admin should be notified about the Digipay refund failure
        } catch (\Exception $e) {
            Log::channel($this->getLogChannel())->error('[Digipay] Unexpected error during automatic refund', [
                'order_id' => $order->id,
                'refund_id' => $refund->id,
                'error' => $e->getMessage(),
            ]);
        }
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
