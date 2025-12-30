<?php

declare(strict_types=1);

namespace DigipayGateway\DataTransferObjects;

class CallbackPayload
{
    private int $amount;
    private string $providerId;
    private string $trackingCode;
    private string $result;
    private int $type;
    private ?string $rrn;
    private ?string $psp;
    private ?string $pspCode;
    private ?string $pspName;

    public function __construct(
        int $amount,
        string $providerId,
        string $trackingCode,
        string $result,
        int $type,
        ?string $rrn = null,
        ?string $psp = null,
        ?string $pspCode = null,
        ?string $pspName = null
    ) {
        $this->amount = $amount;
        $this->providerId = $providerId;
        $this->trackingCode = $trackingCode;
        $this->result = $result;
        $this->type = $type;
        $this->rrn = $rrn;
        $this->psp = $psp;
        $this->pspCode = $pspCode;
        $this->pspName = $pspName;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getProviderId(): string
    {
        return $this->providerId;
    }

    public function getTrackingCode(): string
    {
        return $this->trackingCode;
    }

    public function getResult(): string
    {
        return $this->result;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function getRrn(): ?string
    {
        return $this->rrn;
    }

    public function getPsp(): ?string
    {
        return $this->psp;
    }

    public function getPspCode(): ?string
    {
        return $this->pspCode;
    }

    public function getPspName(): ?string
    {
        return $this->pspName;
    }

    public function isSuccessful(): bool
    {
        return strtoupper($this->result) === 'SUCCESS';
    }

    /**
     * Create from request data.
     *
     * @param array<string, mixed> $data
     * @return self
     */
    public static function fromRequest(array $data): self
    {
        return new self(
            (int) ($data['amount'] ?? 0),
            $data['providerId'] ?? '',
            $data['trackingCode'] ?? '',
            $data['result'] ?? 'FAILURE',
            (int) ($data['type'] ?? 0),
            $data['rrn'] ?? null,
            $data['psp'] ?? null,
            $data['pspCode'] ?? null,
            $data['pspName'] ?? null
        );
    }
}
