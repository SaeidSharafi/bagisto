<?php

declare(strict_types=1);

namespace DigipayGateway\Http\Controllers;

use DigipayGateway\Services\DeliveryRefundService;
use DigipayGateway\Exceptions\DeliverException;
use DigipayGateway\Exceptions\RefundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Webkul\Sales\Repositories\OrderRepository;

/**
 * Admin controller for Digipay delivery and refund operations.
 */
class DigipayAdminController extends Controller
{
    private DeliveryRefundService $deliveryRefundService;
    private OrderRepository $orderRepository;

    public function __construct(
        DeliveryRefundService $deliveryRefundService,
        OrderRepository $orderRepository
    ) {
        $this->deliveryRefundService = $deliveryRefundService;
        $this->orderRepository = $orderRepository;
    }

    /**
     * Confirm delivery for an order.
     * Only applicable for CREDIT and BNPL payments.
     *
     * @param int $orderId
     * @return JsonResponse
     */
    public function confirmDelivery(int $orderId): JsonResponse
    {
        $order = $this->orderRepository->find($orderId);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => __('digipay::messages.order_not_found'),
            ], 404);
        }

        if (!$this->deliveryRefundService->isDigipayOrder($order)) {
            return response()->json([
                'success' => false,
                'message' => __('digipay::messages.not_digipay_order'),
            ], 400);
        }

        try {
            $response = $this->deliveryRefundService->confirmDelivery($order);

            return response()->json([
                'success' => true,
                'message' => __('digipay::messages.delivery_confirmed'),
                'data' => [
                    'order_id' => $orderId,
                    'digipay_message' => $response->getMessage(),
                ],
            ]);
        } catch (DeliverException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getUserMessage(),
                'error_code' => $e->getErrorCode(),
            ], 400);
        }
    }

    /**
     * Refund a payment.
     *
     * @param Request $request
     * @param int $orderId
     * @return JsonResponse
     */
    public function refund(Request $request, int $orderId): JsonResponse
    {
        Log::channel('digipay')->info('[Digipay Admin] Refund request received', [
            'order_id' => $orderId,
            'request_data' => $request->all(),
        ]);

        $order = $this->orderRepository->find($orderId);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => __('digipay::messages.order_not_found'),
            ], 404);
        }

        if (!$this->deliveryRefundService->isDigipayOrder($order)) {
            return response()->json([
                'success' => false,
                'message' => __('digipay::messages.not_digipay_order'),
            ], 400);
        }

        // Get optional refund amount (null for full refund)
        $amount = $request->has('amount') ? (int) $request->get('amount') : null;

        try {
            $response = $this->deliveryRefundService->refund($order, $amount);

            Log::channel('digipay')->info('[Digipay Admin] Refund successful', [
                'order_id' => $orderId,
                'tracking_code' => $response->getTrackingCode(),
            ]);

            return response()->json([
                'success' => true,
                'message' => __('digipay::messages.refund_successful'),
                'data' => [
                    'order_id' => $orderId,
                    'tracking_code' => $response->getTrackingCode(),
                    'digipay_message' => $response->getMessage(),
                ],
            ]);
        } catch (RefundException $e) {
            Log::channel('digipay')->error('[Digipay Admin] Refund failed', [
                'order_id' => $orderId,
                'error' => $e->getUserMessage(),
                'error_code' => $e->getErrorCode(),
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getUserMessage(),
                'error_code' => $e->getErrorCode(),
            ], 400);
        } catch (\Throwable $e) {
            Log::channel('digipay')->error('[Digipay Admin] Refund unexpected error', [
                'order_id' => $orderId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'خطای غیرمنتظره: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Check refund status.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function inquireRefund(Request $request): JsonResponse
    {
        $refundProviderId = $request->get('refund_provider_id');
        $type = (int) $request->get('type', 0);

        if (!$refundProviderId) {
            return response()->json([
                'success' => false,
                'message' => __('digipay::messages.missing_refund_provider_id'),
            ], 400);
        }

        try {
            $response = $this->deliveryRefundService->inquireRefund($refundProviderId, $type);

            $statusText = 'unknown';
            if ($response->isRefundCompleted()) {
                $statusText = 'completed';
            } elseif ($response->isRefundFailed()) {
                $statusText = 'failed';
            } elseif ($response->isRefundPending()) {
                $statusText = 'pending';
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'refund_provider_id' => $refundProviderId,
                    'status' => $statusText,
                    'tracking_code' => $response->getTrackingCode(),
                    'transfer_date' => $response->getTransferDate(),
                    'destination_type' => $response->getDestinationType(),
                    'destination' => $response->getDestination(),
                ],
            ]);
        } catch (RefundException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getUserMessage(),
                'error_code' => $e->getErrorCode(),
            ], 400);
        }
    }

    /**
     * Get Digipay payment info for an order.
     *
     * @param int $orderId
     * @return JsonResponse
     */
    public function getPaymentInfo(int $orderId): JsonResponse
    {
        $order = $this->orderRepository->find($orderId);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => __('digipay::messages.order_not_found'),
            ], 404);
        }

        if (!$this->deliveryRefundService->isDigipayOrder($order)) {
            return response()->json([
                'success' => false,
                'message' => __('digipay::messages.not_digipay_order'),
            ], 400);
        }

        $paymentData = $this->deliveryRefundService->getDigipayPaymentData($order);

        return response()->json([
            'success' => true,
            'data' => [
                'order_id' => $orderId,
                'payment_data' => $paymentData,
                'requires_delivery_confirmation' => $this->deliveryRefundService->requiresDeliveryConfirmation($order),
                'is_delivery_confirmed' => $paymentData['delivery_confirmed'] ?? false,
                'is_refunded' => isset($paymentData['refund_tracking_code']),
            ],
        ]);
    }
}
