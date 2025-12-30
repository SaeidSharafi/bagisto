<?php

declare(strict_types=1);

namespace DigipayGateway\DataTransferObjects;

class VerifyRequest
{
    private string $trackingCode;
    private string $providerId;
    private int $type;

    public function __construct(
        string $trackingCode,
        string $providerId,
        int $type = 0 // 0 = IPG
    ) {
        $this->trackingCode = $trackingCode;
        $this->providerId = $providerId;
        $this->type = $type;
    }

    public function getTrackingCode(): string
    {
        return $this->trackingCode;
    }

    public function getProviderId(): string
    {
        return $this->providerId;
    }

    public function getType(): int
    {
        return $this->type;
    }

    /**
     * Convert to array for API request.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'trackingCode' => $this->trackingCode,
            'providerId' => $this->providerId,
        ];
    }
}
