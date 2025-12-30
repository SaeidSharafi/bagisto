<?php

declare(strict_types=1);

namespace DigipayGateway\DataTransferObjects;

/**
 * DTO for delivery confirmation response.
 */
class DeliverResponse
{
    private int $statusCode;
    private string $message;
    private ?string $level;

    public function __construct(
        int $statusCode,
        string $message,
        ?string $level = null
    ) {
        $this->statusCode = $statusCode;
        $this->message = $message;
        $this->level = $level;
    }

    /**
     * Create from API response.
     *
     * @param array $response
     * @return self
     */
    public static function fromResponse(array $response): self
    {
        $result = $response['result'] ?? [];

        return new self(
            (int) ($result['status'] ?? -1),
            (string) ($result['message'] ?? ''),
            $result['level'] ?? null
        );
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getLevel(): ?string
    {
        return $this->level;
    }

    /**
     * Check if delivery confirmation was successful.
     *
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return $this->statusCode === 0;
    }
}
