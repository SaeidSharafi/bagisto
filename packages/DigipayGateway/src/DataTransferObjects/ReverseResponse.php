<?php

declare(strict_types=1);

namespace DigipayGateway\DataTransferObjects;

class ReverseResponse
{
    private string $trackingCode;
    private ?string $rrn;
    private ?string $maskedPan;
    private int $amount;
    private int $paymentGateway;
    private int $statusCode;
    private string $message;

    public function __construct(
        string $trackingCode,
        ?string $rrn,
        ?string $maskedPan,
        int $amount,
        int $paymentGateway,
        int $statusCode = 0,
        string $message = ''
    ) {
        $this->trackingCode = $trackingCode;
        $this->rrn = $rrn;
        $this->maskedPan = $maskedPan;
        $this->amount = $amount;
        $this->paymentGateway = $paymentGateway;
        $this->statusCode = $statusCode;
        $this->message = $message;
    }

    public function getTrackingCode(): string
    {
        return $this->trackingCode;
    }

    public function getRrn(): ?string
    {
        return $this->rrn;
    }

    public function getMaskedPan(): ?string
    {
        return $this->maskedPan;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getPaymentGateway(): int
    {
        return $this->paymentGateway;
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
        return $this->statusCode === 0;
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
            $response['trackingCode'] ?? '',
            $response['rrn'] ?? null,
            $response['maskedPan'] ?? null,
            (int) ($response['amount'] ?? 0),
            (int) ($response['paymentGateway'] ?? 0),
            (int) ($response['result']['status'] ?? 0),
            $response['result']['message'] ?? ''
        );
    }
}
