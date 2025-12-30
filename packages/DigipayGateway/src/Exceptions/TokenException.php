<?php

declare(strict_types=1);

namespace DigipayGateway\Exceptions;

/**
 * Exception thrown when ticket/token creation fails.
 */
class TokenException extends DigipayException
{
    public static function creationFailed(int $code, string $message, array $responseData = []): self
    {
        return new self(
            "Failed to create payment ticket: {$message}",
            $code,
            [
                'type' => 'ticket_creation_failed',
                'response_data' => $responseData,
            ]
        );
    }

    public static function invalidResponse(string $reason = ''): self
    {
        return new self(
            "Invalid ticket response from gateway: {$reason}",
            0,
            ['type' => 'invalid_response', 'reason' => $reason]
        );
    }

    public static function missingRedirectUrl(): self
    {
        return new self(
            'Gateway did not return a redirect URL',
            0,
            ['type' => 'missing_redirect_url']
        );
    }
}
