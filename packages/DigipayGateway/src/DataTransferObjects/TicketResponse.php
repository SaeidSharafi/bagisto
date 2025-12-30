<?php

declare(strict_types=1);

namespace DigipayGateway\DataTransferObjects;

class TicketResponse
{
    private string $redirectUrl;
    private string $ticket;
    private int $statusCode;
    private string $message;

    public function __construct(
        string $redirectUrl,
        string $ticket,
        int $statusCode = 0,
        string $message = ''
    ) {
        $this->redirectUrl = $redirectUrl;
        $this->ticket = $ticket;
        $this->statusCode = $statusCode;
        $this->message = $message;
    }

    public function getRedirectUrl(): string
    {
        return $this->redirectUrl;
    }

    public function getTicket(): string
    {
        return $this->ticket;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function isSuccessful(): bool
    {
        return $this->statusCode === 0 && !empty($this->redirectUrl);
    }

    /**
     * Create from API response.
     *
     * @param array<string, mixed> $response
     * @return self
     */
    public static function fromResponse(array $response): self
    {
        return new self(
            $response['redirectUrl'] ?? '',
            $response['ticket'] ?? '',
            (int) ($response['result']['status'] ?? 0),
            $response['result']['message'] ?? ''
        );
    }
}
