<?php

declare(strict_types=1);

namespace DigipayGateway\Exceptions;

/**
 * Exception for delivery confirmation failures.
 */
class DeliverException extends DigipayException
{
    /**
     * Create exception for delivery failure.
     *
     * @param int $statusCode
     * @param string $message
     * @param string $trackingCode
     * @return self
     */
    public static function failed(int $statusCode, string $message, string $trackingCode): self
    {
        return new self(
            sprintf('Delivery confirmation failed: %s', $message),
            $statusCode,
            [
                'type' => 'delivery_failed',
                'tracking_code' => $trackingCode,
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
            sprintf('Order %d is not eligible for delivery confirmation. Current status: %s', $orderId, $currentStatus),
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
     * Create exception for unsupported payment type.
     *
     * @param int $type
     * @return self
     */
    public static function unsupportedPaymentType(int $type): self
    {
        return new self(
            sprintf('Delivery confirmation is only supported for CREDIT and BNPL payments. Type: %d', $type),
            400,
            [
                'type' => 'unsupported_payment_type',
                'payment_type' => $type,
            ]
        );
    }
}
