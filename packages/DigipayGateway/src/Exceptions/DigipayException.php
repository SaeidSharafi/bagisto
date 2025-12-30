<?php

declare(strict_types=1);

namespace DigipayGateway\Exceptions;

use Exception;
use DigipayGateway\Enums\PaymentStatus;

/**
 * Base exception for all Digipay-related errors.
 */
class DigipayException extends Exception
{
    protected int $errorCode;
    protected array $context;

    public function __construct(
        string $message = '',
        int $errorCode = 0,
        array $context = [],
        ?Exception $previous = null
    ) {
        $this->errorCode = $errorCode;
        $this->context = $context;

        parent::__construct($message, $errorCode, $previous);
    }

    /**
     * Get the Digipay error code.
     *
     * @return int
     */
    public function getErrorCode(): int
    {
        return $this->errorCode;
    }

    /**
     * Get additional context data.
     *
     * @return array<string, mixed>
     */
    public function getContext(): array
    {
        return $this->context;
    }

    /**
     * Get user-friendly error message.
     *
     * @return string
     */
    public function getUserMessage(): string
    {
        return PaymentStatus::getMessage($this->errorCode);
    }

    /**
     * Convert to array for logging.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'exception' => static::class,
            'message' => $this->getMessage(),
            'error_code' => $this->errorCode,
            'context' => $this->context,
            'file' => $this->getFile(),
            'line' => $this->getLine(),
        ];
    }
}
