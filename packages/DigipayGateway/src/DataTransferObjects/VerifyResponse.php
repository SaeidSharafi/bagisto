<?php

declare(strict_types=1);

namespace DigipayGateway\DataTransferObjects;

class VerifyResponse
{
    private string $trackingCode;
    private string $providerId;
    private int $amount;
    private ?string $rrn;
    private ?string $maskedPan;
    private ?string $pspName;
    private ?string $terminalId;
    private int $paymentGateway;
    private int $statusCode;
    private string $message;

    public function __construct(
        string $trackingCode,
        string $providerId,
        int $amount,
        ?string $rrn,
        ?string $maskedPan,
        ?string $pspName,
        ?string $terminalId,
        int $paymentGateway,
        int $statusCode = 0,
        string $message = ''
    ) {
        $this->trackingCode = $trackingCode;
        $this->providerId = $providerId;
        $this->amount = $amount;
        $this->rrn = $rrn;
        $this->maskedPan = $maskedPan;
        $this->pspName = $pspName;
        $this->terminalId = $terminalId;
        $this->paymentGateway = $paymentGateway;
        $this->statusCode = $statusCode;
        $this->message = $message;
    }

    public function getTrackingCode(): string
    {
        return $this->trackingCode;
    }

    public function getProviderId(): string
    {
        return $this->providerId;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getRrn(): ?string
    {
        return $this->rrn;
    }

    public function getMaskedPan(): ?string
    {
        return $this->maskedPan;
    }

    public function getPspName(): ?string
    {
        return $this->pspName;
    }

    public function getTerminalId(): ?string
    {
        return $this->terminalId;
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
     * Convert to array for transaction storage.
     *
     * @return array<string, mixed>
     */
    public function toTransactionData(): array
    {
        return [
            'tracking_code' => $this->trackingCode,
            'provider_id' => $this->providerId,
            'amount' => $this->amount,
            'rrn' => $this->rrn,
            'masked_pan' => $this->maskedPan,
            'psp_name' => $this->pspName,
            'terminal_id' => $this->terminalId,
            'payment_gateway' => $this->paymentGateway,
        ];
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
            $response['providerId'] ?? '',
            (int) ($response['amount'] ?? 0),
            $response['rrn'] ?? null,
            $response['maskedPan'] ?? null,
            $response['pspName'] ?? null,
            $response['terminalId'] ?? null,
            (int) ($response['paymentGateway'] ?? 0),
            (int) ($response['result']['status'] ?? 0),
            $response['result']['message'] ?? ''
        );
    }
}
