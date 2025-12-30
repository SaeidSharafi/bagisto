<?php

declare(strict_types=1);

namespace DigipayGateway\DataTransferObjects;

class TicketRequest
{
    private int $amount;
    private string $cellNumber;
    private string $providerId;
    private string $callbackUrl;
    private ?string $description;

    public function __construct(
        int $amount,
        string $cellNumber,
        string $providerId,
        string $callbackUrl,
        ?string $description = null
    ) {
        $this->amount = $amount;
        $this->cellNumber = $cellNumber;
        $this->providerId = $providerId;
        $this->callbackUrl = $callbackUrl;
        $this->description = $description;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getCellNumber(): string
    {
        return $this->cellNumber;
    }

    public function getProviderId(): string
    {
        return $this->providerId;
    }

    public function getCallbackUrl(): string
    {
        return $this->callbackUrl;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Convert to array for API request.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $data = [
            'amount' => $this->amount,
            'cellNumber' => $this->cellNumber,
            'providerId' => $this->providerId,
            'callbackUrl' => $this->callbackUrl,
        ];

        if ($this->description !== null) {
            $data['additionalInfo'] = [
                'description' => $this->description,
            ];
        }

        return $data;
    }

    /**
     * Create from order data.
     *
     * @param array<string, mixed> $orderData
     * @return self
     */
    public static function fromOrder(array $orderData): self
    {
        return new self(
            (int) ($orderData['amount'] ?? 0),
            $orderData['cellNumber'] ?? '',
            $orderData['providerId'] ?? '',
            $orderData['callbackUrl'] ?? '',
            $orderData['description'] ?? null
        );
    }
}
