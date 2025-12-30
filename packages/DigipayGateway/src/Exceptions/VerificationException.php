<?php

declare(strict_types=1);

namespace DigipayGateway\Exceptions;

/**
 * Exception thrown when payment verification fails.
 */
class VerificationException extends DigipayException
{
    private ?string $providerId;
    private ?string $trackingCode;

    public function __construct(
        string $message,
        int $errorCode = 0,
        array $context = [],
        ?string $providerId = null,
        ?string $trackingCode = null
    ) {
        parent::__construct($message, $errorCode, $context);
        $this->providerId = $providerId;
        $this->trackingCode = $trackingCode;
    }

    public function getProviderId(): ?string
    {
        return $this->providerId;
    }

    public function getTrackingCode(): ?string
    {
        return $this->trackingCode;
    }

    public static function failed(int $code, string $message, ?string $providerId = null, ?string $trackingCode = null): self
    {
        return new self(
            "Payment verification failed: {$message}",
            $code,
            ['type' => 'verification_failed'],
            $providerId,
            $trackingCode
        );
    }

    public static function amountMismatch(int $expected, int $received, ?string $providerId = null): self
    {
        return new self(
            "Amount mismatch: expected {$expected}, received {$received}",
            0,
            ['type' => 'amount_mismatch', 'expected' => $expected, 'received' => $received],
            $providerId,
            null
        );
    }

    public static function providerIdMismatch(string $expected, string $received): self
    {
        return new self(
            "Provider ID mismatch: expected {$expected}, received {$received}",
            0,
            ['type' => 'provider_id_mismatch', 'expected' => $expected, 'received' => $received],
            $received,
            null
        );
    }

    public static function timeout(?string $providerId = null, ?string $trackingCode = null): self
    {
        return new self(
            'Payment verification timed out',
            9009,
            ['type' => 'verification_timeout'],
            $providerId,
            $trackingCode
        );
    }

    public static function indeterminate(?string $providerId = null, ?string $trackingCode = null): self
    {
        return new self(
            'Payment verification status is indeterminate',
            9011,
            ['type' => 'verification_indeterminate'],
            $providerId,
            $trackingCode
        );
    }
}
