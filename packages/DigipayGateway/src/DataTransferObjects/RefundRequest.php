<?php

declare(strict_types=1);

namespace DigipayGateway\DataTransferObjects;

/**
 * DTO for refund request.
 * Used to refund a payment after verification.
 */
class RefundRequest
{
    private string $providerId;
    private int $amount;
    private string $saleTrackingCode;
    private int $type;

    /**
     * @param string $providerId Unique ID for refund (different from purchase providerId)
     * @param int $amount Amount to refund
     * @param string $saleTrackingCode Tracking code from original purchase
     * @param int $type Payment type (0=IPG, 11=Wallet, 5=CREDIT, 13=BNPL, 24=CREDIT-CARD)
     */
    public function __construct(
        string $providerId,
        int $amount,
        string $saleTrackingCode,
        int $type
    ) {
        $this->providerId = $providerId;
        $this->amount = $amount;
        $this->saleTrackingCode = $saleTrackingCode;
        $this->type = $type;
    }

    /**
     * Create from order for full refund.
     *
     * @param \Webkul\Sales\Models\Order $order
     * @param string $trackingCode Original payment tracking code
     * @param int $type Payment type
     * @return self
     */
    public static function fromOrder($order, string $trackingCode, int $type): self
    {
        return new self(
            'REFUND-' . $order->id . '-' . time(), // Unique refund ID
            (int) $order->grand_total,
            $trackingCode,
            $type
        );
    }

    /**
     * Create partial refund.
     *
     * @param \Webkul\Sales\Models\Order $order
     * @param int $amount Amount to refund
     * @param string $trackingCode Original payment tracking code
     * @param int $type Payment type
     * @return self
     */
    public static function partialRefund($order, int $amount, string $trackingCode, int $type): self
    {
        return new self(
            'REFUND-' . $order->id . '-' . time(),
            $amount,
            $trackingCode,
            $type
        );
    }

    public function getProviderId(): string
    {
        return $this->providerId;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getSaleTrackingCode(): string
    {
        return $this->saleTrackingCode;
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
            'providerId' => $this->providerId,
            'amount' => $this->amount,
            'saleTrackingCode' => $this->saleTrackingCode,
        ];
    }
}
