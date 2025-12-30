<?php

declare(strict_types=1);

namespace DigipayGateway\Contracts;

use DigipayGateway\DataTransferObjects\TicketRequest;
use DigipayGateway\DataTransferObjects\TicketResponse;
use DigipayGateway\DataTransferObjects\VerifyRequest;
use DigipayGateway\DataTransferObjects\VerifyResponse;
use DigipayGateway\DataTransferObjects\ReverseRequest;
use DigipayGateway\DataTransferObjects\ReverseResponse;
use DigipayGateway\DataTransferObjects\DeliverRequest;
use DigipayGateway\DataTransferObjects\DeliverResponse;
use DigipayGateway\DataTransferObjects\RefundRequest;
use DigipayGateway\DataTransferObjects\RefundResponse;
use DigipayGateway\DataTransferObjects\RefundInquiryResponse;
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
     * Confirm delivery of order (only for CREDIT and BNPL payments).
     *
     * @param DeliverRequest $request
     * @return DeliverResponse
     * @throws DigipayException
     */
    public function deliver(DeliverRequest $request): DeliverResponse;

    /**
     * Refund a payment.
     *
     * @param RefundRequest $request
     * @return RefundResponse
     * @throws DigipayException
     */
    public function refund(RefundRequest $request): RefundResponse;

    /**
     * Inquire about a refund status.
     *
     * @param string $refundProviderId The providerId used when creating the refund
     * @param int $type Payment type
     * @return RefundInquiryResponse
     * @throws DigipayException
     */
    public function inquireRefund(string $refundProviderId, int $type): RefundInquiryResponse;

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
