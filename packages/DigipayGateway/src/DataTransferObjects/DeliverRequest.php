<?php

declare(strict_types=1);

namespace DigipayGateway\DataTransferObjects;

/**
 * DTO for delivery confirmation request.
 * Used when order is delivered (only for CREDIT and BNPL payments).
 */
class DeliverRequest
{
    private int $deliveryDate;
    private string $invoiceNumber;
    private string $trackingCode;
    private array $products;
    private int $type;

    /**
     * @param int $deliveryDate Unix timestamp in milliseconds
     * @param string $invoiceNumber Invoice/order number
     * @param string $trackingCode Digipay tracking code from payment
     * @param array $products List of product identifiers delivered
     * @param int $type Payment type (5 = CREDIT, 13 = BNPL)
     */
    public function __construct(
        int $deliveryDate,
        string $invoiceNumber,
        string $trackingCode,
        array $products,
        int $type
    ) {
        $this->deliveryDate = $deliveryDate;
        $this->invoiceNumber = $invoiceNumber;
        $this->trackingCode = $trackingCode;
        $this->products = $products;
        $this->type = $type;
    }

    /**
     * Create from order data.
     *
     * @param \Webkul\Sales\Models\Order $order
     * @param string $trackingCode
     * @param int $type
     * @return self
     */
    public static function fromOrder($order, string $trackingCode, int $type): self
    {
        $products = [];
        foreach ($order->items as $item) {
            $products[] = 'product-' . $item->product_id;
        }

        return new self(
            (int) (now()->timestamp * 1000), // Unix timestamp in milliseconds
            (string) $order->id, // Use order ID as invoice number
            $trackingCode,
            $products,
            $type
        );
    }

    public function getDeliveryDate(): int
    {
        return $this->deliveryDate;
    }

    public function getInvoiceNumber(): string
    {
        return $this->invoiceNumber;
    }

    public function getTrackingCode(): string
    {
        return $this->trackingCode;
    }

    public function getProducts(): array
    {
        return $this->products;
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
            'deliveryDate' => $this->deliveryDate,
            'invoiceNumber' => $this->invoiceNumber,
            'trackingCode' => $this->trackingCode,
            'products' => $this->products,
        ];
    }
}
