<?php

declare(strict_types=1);

namespace DigipayGateway\Listeners;

use DigipayGateway\Services\DeliveryRefundService;
use DigipayGateway\Exceptions\DeliverException;
use Illuminate\Support\Facades\Log;
use Webkul\Sales\Models\Order;

/**
 * Listener for order events to handle Digipay delivery confirmation.
 */
class OrderStatusListener
{
    private DeliveryRefundService $deliveryRefundService;

    public function __construct(DeliveryRefundService $deliveryRefundService)
    {
        $this->deliveryRefundService = $deliveryRefundService;
    }

    /**
     * Handle BEFORE order status update event.
     * Called before the order status is actually changed.
     * If delivery fails, throws exception to block the order completion.
     *
     * @param Order $order
     * @param string|null $newStatus The status that will be set
     * @return void
     * @throws DeliverException If delivery confirmation fails
     */
    public function onBeforeOrderStatusUpdate($order, ?string $newStatus = null): void
    {
        // Only process if transitioning to "completed" status
        if ($newStatus !== 'completed') {
            return;
        }

        // Only process Digipay orders
        if (!$this->deliveryRefundService->isDigipayOrder($order)) {
            return;
        }

        // Only process if delivery confirmation is required (CREDIT/BNPL)
        if (!$this->deliveryRefundService->requiresDeliveryConfirmation($order)) {
            Log::channel($this->getLogChannel())->info('[Digipay] Order completing but delivery confirmation not required', [
                'order_id' => $order->id,
            ]);
            return;
        }

        // Check if already confirmed
        $paymentData = $this->deliveryRefundService->getDigipayPaymentData($order);
        if (isset($paymentData['delivery_confirmed']) && $paymentData['delivery_confirmed']) {
            Log::channel($this->getLogChannel())->info('[Digipay] Delivery already confirmed', [
                'order_id' => $order->id,
            ]);
            return;
        }

        Log::channel($this->getLogChannel())->info('[Digipay] Attempting delivery confirmation before completing order', [
            'order_id' => $order->id,
        ]);

        // This will throw DeliverException if it fails, blocking order completion
        $this->deliveryRefundService->confirmDelivery($order);

        Log::channel($this->getLogChannel())->info('[Digipay] Delivery confirmed successfully, order can be completed', [
            'order_id' => $order->id,
        ]);
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
