<?php

declare(strict_types=1);

namespace DigipayGateway\DataTransferObjects;

/**
 * DTO for refund inquiry response.
 * Used to check status of a refund.
 */
class RefundInquiryResponse
{
    private int $statusCode;
    private string $message;
    private ?string $title;
    private ?string $level;
    private ?string $providerId;
    private ?string $trackingCode;
    private ?int $status;
    private ?int $resultCode;
    private ?string $transferDate;
    private ?int $destinationType;
    private ?string $destination;

    public function __construct(
        int $statusCode,
        string $message,
        ?string $title = null,
        ?string $level = null,
        ?string $providerId = null,
        ?string $trackingCode = null,
        ?int $status = null,
        ?int $resultCode = null,
        ?string $transferDate = null,
        ?int $destinationType = null,
        ?string $destination = null
    ) {
        $this->statusCode = $statusCode;
        $this->message = $message;
        $this->title = $title;
        $this->level = $level;
        $this->providerId = $providerId;
        $this->trackingCode = $trackingCode;
        $this->status = $status;
        $this->resultCode = $resultCode;
        $this->transferDate = $transferDate;
        $this->destinationType = $destinationType;
        $this->destination = $destination;
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
            $response['providerId'] ?? null,
            $response['trackingCode'] ?? null,
            isset($response['status']) ? (int) $response['status'] : null,
            isset($response['resultCode']) ? (int) $response['resultCode'] : null,
            $response['transferDate'] ?? null,
            isset($response['destinationType']) ? (int) $response['destinationType'] : null,
            $response['destination'] ?? null
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

    public function getProviderId(): ?string
    {
        return $this->providerId;
    }

    public function getTrackingCode(): ?string
    {
        return $this->trackingCode;
    }

    /**
     * Get refund status.
     * 0 = Success, 1 = Failed, 2 = Unknown (needs inquiry)
     *
     * @return int|null
     */
    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function getResultCode(): ?int
    {
        return $this->resultCode;
    }

    public function getTransferDate(): ?string
    {
        return $this->transferDate;
    }

    /**
     * Get destination type.
     * 0 = Masked PAN, 1 = IBAN, 2 = Wallet, 3 = Credit
     *
     * @return int|null
     */
    public function getDestinationType(): ?int
    {
        return $this->destinationType;
    }

    public function getDestination(): ?string
    {
        return $this->destination;
    }

    /**
     * Check if API call was successful.
     *
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return $this->statusCode === 0;
    }

    /**
     * Check if refund completed successfully.
     *
     * @return bool
     */
    public function isRefundCompleted(): bool
    {
        return $this->status === 0;
    }

    /**
     * Check if refund failed.
     *
     * @return bool
     */
    public function isRefundFailed(): bool
    {
        return $this->status === 1;
    }

    /**
     * Check if refund status is unknown (needs retry).
     *
     * @return bool
     */
    public function isRefundPending(): bool
    {
        return $this->status === 2;
    }
}
