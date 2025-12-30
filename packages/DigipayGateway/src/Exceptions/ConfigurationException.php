<?php

declare(strict_types=1);

namespace DigipayGateway\Exceptions;

/**
 * Exception thrown when configuration is invalid or missing.
 */
class ConfigurationException extends DigipayException
{
    public static function missingField(string $field): self
    {
        return new self(
            "Missing required configuration: {$field}",
            0,
            ['type' => 'missing_field', 'field' => $field]
        );
    }

    public static function invalidField(string $field, string $reason = ''): self
    {
        return new self(
            "Invalid configuration for {$field}: {$reason}",
            0,
            ['type' => 'invalid_field', 'field' => $field, 'reason' => $reason]
        );
    }

    public static function notActive(): self
    {
        return new self(
            'Digipay payment method is not active',
            0,
            ['type' => 'not_active']
        );
    }
}
