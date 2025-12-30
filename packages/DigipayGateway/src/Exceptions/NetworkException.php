<?php

declare(strict_types=1);

namespace DigipayGateway\Exceptions;

/**
 * Exception thrown when network/HTTP errors occur.
 */
class NetworkException extends DigipayException
{
    public static function connectionFailed(string $url, string $reason = ''): self
    {
        return new self(
            "Failed to connect to {$url}: {$reason}",
            0,
            ['type' => 'connection_failed', 'url' => $url, 'reason' => $reason]
        );
    }

    public static function timeout(string $url, int $timeout): self
    {
        return new self(
            "Request to {$url} timed out after {$timeout} seconds",
            0,
            ['type' => 'timeout', 'url' => $url, 'timeout' => $timeout]
        );
    }

    public static function invalidResponse(string $url, string $reason = ''): self
    {
        return new self(
            "Invalid response from {$url}: {$reason}",
            0,
            ['type' => 'invalid_response', 'url' => $url, 'reason' => $reason]
        );
    }

    public static function httpError(string $url, int $statusCode, string $body = ''): self
    {
        // Try to decode JSON body for better error message
        $decodedBody = json_decode($body, true);
        $errorMessage = "HTTP {$statusCode} error from {$url}";

        if ($decodedBody) {
            // Extract error details from Digipay response
            if (isset($decodedBody['message'])) {
                $errorMessage .= " - " . $decodedBody['message'];
            }
            if (isset($decodedBody['errors'])) {
                $errorMessage .= " - Errors: " . json_encode($decodedBody['errors'], JSON_UNESCAPED_UNICODE);
            }
        }

        return new self(
            $errorMessage,
            $statusCode,
            [
                'type' => 'http_error',
                'url' => $url,
                'status_code' => $statusCode,
                'body' => $body,
                'decoded_body' => $decodedBody,
            ]
        );
    }

    /**
     * Get the response body.
     *
     * @return string|null
     */
    public function getResponseBody(): ?string
    {
        return $this->context['body'] ?? null;
    }

    /**
     * Get decoded response body.
     *
     * @return array|null
     */
    public function getDecodedBody(): ?array
    {
        return $this->context['decoded_body'] ?? null;
    }
}
