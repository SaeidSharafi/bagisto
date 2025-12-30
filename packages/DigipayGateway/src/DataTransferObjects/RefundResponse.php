<?php

declare(strict_types=1);

namespace DigipayGateway\DataTransferObjects;

/**
 * DTO for refund response.
 */
class RefundResponse
{
    private int $statusCode;
    private string $message;
    private ?string $title;
    private ?string $level;
    private ?string $trackingCode;

    public function __construct(
        int $statusCode,
        string $message,
        ?string $title = null,
        ?string $level = null,
        ?string $trackingCode = null
    ) {
        $this->statusCode = $statusCode;
        $this->message = $message;
        $this->title = $title;
        $this->level = $level;
        $this->trackingCode = $trackingCode;
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
            $result['title'] ?? null,
            $result['level'] ?? null,
            $response['trackingCode'] ?? null
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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getLevel(): ?string
    {
        return $this->level;
    }

    /**
     * Get the refund tracking code.
     *
     * @return string|null
     */
    public function getTrackingCode(): ?string
    {
        return $this->trackingCode;
    }

    /**
     * Check if refund was successful.
     *
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return $this->statusCode === 0;
    }
}
