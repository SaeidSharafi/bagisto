<?php

declare(strict_types=1);

namespace DigipayGateway\Exceptions;

/**
 * Exception thrown when payment reversal fails.
 */
class ReverseException extends DigipayException
{
    public static function failed(int $code, string $message, ?string $trackingCode = null): self
    {
        return new self(
            "Payment reversal failed: {$message}",
            $code,
            ['type' => 'reverse_failed', 'tracking_code' => $trackingCode]
        );
    }

    public static function windowExpired(?string $trackingCode = null): self
    {
        return new self(
            'Reversal window has expired (must be within 25 minutes of verification)',
            0,
            ['type' => 'window_expired', 'tracking_code' => $trackingCode]
        );
    }

    public static function notAllowed(string $reason = '', ?string $trackingCode = null): self
    {
        return new self(
            "Reversal not allowed: {$reason}",
            0,
            ['type' => 'not_allowed', 'reason' => $reason, 'tracking_code' => $trackingCode]
        );
    }
}
