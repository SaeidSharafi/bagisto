<?php

declare(strict_types=1);

namespace DigipayGateway\Exceptions;

/**
 * Exception thrown when order is not found.
 */
class OrderNotFoundException extends DigipayException
{
    private ?int $orderId;

    public function __construct(
        string $message,
        ?int $orderId = null,
        array $context = []
    ) {
        $context['order_id'] = $orderId;
        parent::__construct($message, 0, $context);
        $this->orderId = $orderId;
    }

    public function getOrderId(): ?int
    {
        return $this->orderId;
    }

    public static function byId(int $orderId): self
    {
        return new self(
            "Order #{$orderId} not found",
            $orderId
        );
    }

    public static function byProviderId(string $providerId): self
    {
        return new self(
            "Order with provider ID {$providerId} not found",
            null,
            ['provider_id' => $providerId]
        );
    }
}
