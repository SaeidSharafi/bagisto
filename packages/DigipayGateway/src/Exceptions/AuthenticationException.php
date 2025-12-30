<?php

declare(strict_types=1);

namespace DigipayGateway\Exceptions;

/**
 * Exception thrown when OAuth authentication fails.
 */
class AuthenticationException extends DigipayException
{
    public static function invalidCredentials(): self
    {
        return new self(
            'Invalid client credentials or user credentials',
            401,
            ['type' => 'invalid_credentials']
        );
    }

    public static function tokenExpired(): self
    {
        return new self(
            'Access token has expired',
            401,
            ['type' => 'token_expired']
        );
    }

    public static function tokenRefreshFailed(string $reason = ''): self
    {
        return new self(
            'Failed to refresh access token: ' . $reason,
            401,
            ['type' => 'refresh_failed', 'reason' => $reason]
        );
    }
}
