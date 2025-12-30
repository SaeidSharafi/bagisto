<?php

declare(strict_types=1);

namespace DigipayGateway\Contracts;

use DigipayGateway\DataTransferObjects\TicketRequest;
use DigipayGateway\DataTransferObjects\TicketResponse;
use DigipayGateway\DataTransferObjects\VerifyRequest;
use DigipayGateway\DataTransferObjects\VerifyResponse;
use DigipayGateway\DataTransferObjects\ReverseRequest;
use DigipayGateway\DataTransferObjects\ReverseResponse;
use DigipayGateway\Exceptions\DigipayException;

interface PaymentGatewayInterface
{
    /**
     * Create a payment ticket and get redirect URL.
     *
     * @param TicketRequest $request
     * @return TicketResponse
     * @throws DigipayException
     */
    public function createTicket(TicketRequest $request): TicketResponse;

    /**
     * Verify a completed payment.
     *
     * @param VerifyRequest $request
     * @return VerifyResponse
     * @throws DigipayException
     */
    public function verify(VerifyRequest $request): VerifyResponse;

    /**
     * Reverse a payment (within 25 minutes of verification).
     *
     * @param ReverseRequest $request
     * @return ReverseResponse
     * @throws DigipayException
     */
    public function reverse(ReverseRequest $request): ReverseResponse;

    /**
     * Check if sandbox mode is enabled.
     *
     * @return bool
     */
    public function isSandbox(): bool;

    /**
     * Get the base API URL based on environment.
     *
     * @return string
     */
    public function getBaseUrl(): string;
}
