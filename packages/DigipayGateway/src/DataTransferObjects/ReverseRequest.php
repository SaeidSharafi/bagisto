<?php

declare(strict_types=1);

namespace DigipayGateway\DataTransferObjects;

class ReverseRequest
{
    private string $purchaseTrackingCode;
    private string $providerId;

    public function __construct(
        string $purchaseTrackingCode,
        string $providerId
    ) {
        $this->purchaseTrackingCode = $purchaseTrackingCode;
        $this->providerId = $providerId;
    }

    public function getPurchaseTrackingCode(): string
    {
        return $this->purchaseTrackingCode;
    }

    public function getProviderId(): string
    {
        return $this->providerId;
    }

    /**
     * Convert to array for API request.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'purchaseTrackingCode' => $this->purchaseTrackingCode,
            'providerId' => $this->providerId,
        ];
    }
}
