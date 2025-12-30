<?php

declare(strict_types=1);

namespace DigipayGateway\Exceptions;

/**
 * Exception for refund failures.
 */
class RefundException extends DigipayException
{
    /**
     * Create exception for refund failure.
     *
     * @param int $statusCode
     * @param string $message
     * @param string $saleTrackingCode
     * @return self
     */
    public static function failed(int $statusCode, string $message, string $saleTrackingCode): self
    {
        return new self(
            sprintf('Refund failed: %s', $message),
            $statusCode,
            [
                'type' => 'refund_failed',
                'sale_tracking_code' => $saleTrackingCode,
            ]
        );
    }

    /**
     * Create exception for invalid order status.
     *
     * @param int $orderId
     * @param string $currentStatus
     * @return self
     */
    public static function invalidOrderStatus(int $orderId, string $currentStatus): self
    {
        return new self(
            sprintf('Order %d is not eligible for refund. Current status: %s', $orderId, $currentStatus),
            400,
            [
                'type' => 'invalid_order_status',
                'order_id' => $orderId,
                'current_status' => $currentStatus,
            ]
        );
    }

    /**
     * Create exception for missing tracking code.
     *
     * @param int $orderId
     * @return self
     */
    public static function missingTrackingCode(int $orderId): self
    {
        return new self(
            sprintf('Order %d does not have a Digipay tracking code', $orderId),
            400,
            [
                'type' => 'missing_tracking_code',
                'order_id' => $orderId,
            ]
        );
    }

    /**
     * Create exception for already refunded order.
     *
     * @param int $orderId
     * @return self
     */
    public static function alreadyRefunded(int $orderId): self
    {
        return new self(
            sprintf('Order %d has already been refunded', $orderId),
            400,
            [
                'type' => 'already_refunded',
                'order_id' => $orderId,
            ]
        );
    }

    /**
     * Create exception for inquiry failure.
     *
     * @param string $refundProviderId
     * @param string $message
     * @return self
     */
    public static function inquiryFailed(string $refundProviderId, string $message): self
    {
        return new self(
            sprintf('Refund inquiry failed: %s', $message),
            500,
            [
                'type' => 'inquiry_failed',
                'refund_provider_id' => $refundProviderId,
            ]
        );
    }

    /**
     * Create exception when refund amount exceeds order total.
     *
     * @param int $orderId
     * @param int $requestedAmount
     * @param int $maxAmount
     * @return self
     */
    public static function amountExceedsTotal(int $orderId, int $requestedAmount, int $maxAmount): self
    {
        return new self(
            sprintf('Refund amount %d exceeds order total %d', $requestedAmount, $maxAmount),
            400,
            [
                'type' => 'amount_exceeds_total',
                'order_id' => $orderId,
                'requested_amount' => $requestedAmount,
                'max_amount' => $maxAmount,
            ]
        );
    }
}
