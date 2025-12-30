<?php

declare(strict_types=1);

namespace DigipayGateway\Infrastructure;

use DigipayGateway\Contracts\PaymentGatewayInterface;
use DigipayGateway\Contracts\AuthenticatorInterface;
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
use DigipayGateway\Exceptions\TokenException;
use DigipayGateway\Exceptions\VerificationException;
use DigipayGateway\Exceptions\ReverseException;
use DigipayGateway\Exceptions\DeliverException;
use DigipayGateway\Exceptions\RefundException;
use DigipayGateway\Exceptions\NetworkException;
use DigipayGateway\Enums\PaymentStatus;

class DigipayClient implements PaymentGatewayInterface
{
    private HttpClient $httpClient;
    private AuthenticatorInterface $authenticator;
    private ConfigRepository $config;

    public function __construct(
        HttpClient $httpClient,
        AuthenticatorInterface $authenticator,
        ConfigRepository $config
    ) {
        $this->httpClient = $httpClient;
        $this->authenticator = $authenticator;
        $this->config = $config;
    }

    /**
     * Create a payment ticket and get redirect URL.
     *
     * @param TicketRequest $request
     * @return TicketResponse
     * @throws TokenException
     */
    public function createTicket(TicketRequest $request): TicketResponse
    {
        $this->prepareClient();

        $ticketType = config('digipay.ticket_type', 11);
        $endpoint = config('digipay.paths.ticket') . '?type=' . $ticketType;

        try {
            $response = $this->httpClient->post($endpoint, $request->toArray());

            $ticketResponse = TicketResponse::fromResponse($response);

            if (!$ticketResponse->isSuccessful()) {
                throw TokenException::creationFailed(
                    $ticketResponse->getStatusCode(),
                    $ticketResponse->getMessage()
                );
            }

            if (empty($ticketResponse->getRedirectUrl())) {
                throw TokenException::missingRedirectUrl();
            }

            return $ticketResponse;
        } catch (NetworkException $e) {
            throw TokenException::creationFailed(
                $e->getErrorCode(),
                $e->getMessage(),
                $e->getContext()
            );
        }
    }

    /**
     * Verify a completed payment.
     *
     * @param VerifyRequest $request
     * @return VerifyResponse
     * @throws VerificationException
     */
    public function verify(VerifyRequest $request): VerifyResponse
    {
        $this->prepareClient();

        $endpoint = config('digipay.paths.verify') . '?type=' . $request->getType();

        try {
            $response = $this->httpClient->post($endpoint, $request->toArray());

            $verifyResponse = VerifyResponse::fromResponse($response);

            if (!$verifyResponse->isSuccessful()) {
                $statusCode = $verifyResponse->getStatusCode();

                // Handle specific error codes
                if ($statusCode === PaymentStatus::VERIFY_TIMEOUT) {
                    throw VerificationException::timeout(
                        $request->getProviderId(),
                        $request->getTrackingCode()
                    );
                }

                if ($statusCode === PaymentStatus::VERIFY_INDETERMINATE) {
                    throw VerificationException::indeterminate(
                        $request->getProviderId(),
                        $request->getTrackingCode()
                    );
                }

                throw VerificationException::failed(
                    $statusCode,
                    $verifyResponse->getMessage(),
                    $request->getProviderId(),
                    $request->getTrackingCode()
                );
            }

            return $verifyResponse;
        } catch (NetworkException $e) {
            throw VerificationException::failed(
                0,
                $e->getMessage(),
                $request->getProviderId(),
                $request->getTrackingCode()
            );
        }
    }

    /**
     * Reverse a payment (within 25 minutes of verification).
     *
     * @param ReverseRequest $request
     * @return ReverseResponse
     * @throws ReverseException
     */
    public function reverse(ReverseRequest $request): ReverseResponse
    {
        $this->prepareClient();

        $endpoint = config('digipay.paths.reverse');

        try {
            $response = $this->httpClient->post($endpoint, $request->toArray());

            $reverseResponse = ReverseResponse::fromResponse($response);

            if (!$reverseResponse->isSuccessful()) {
                throw ReverseException::failed(
                    $reverseResponse->getStatusCode(),
                    $reverseResponse->getMessage(),
                    $request->getPurchaseTrackingCode()
                );
            }

            return $reverseResponse;
        } catch (NetworkException $e) {
            throw ReverseException::failed(
                0,
                $e->getMessage(),
                $request->getPurchaseTrackingCode()
            );
        }
    }

    /**
     * Confirm delivery of order (only for CREDIT and BNPL payments).
     *
     * @param DeliverRequest $request
     * @return DeliverResponse
     * @throws DeliverException
     */
    public function deliver(DeliverRequest $request): DeliverResponse
    {
        $this->prepareClient();

        $endpoint = config('digipay.paths.deliver') . '?type=' . $request->getType();

        try {
            $response = $this->httpClient->post($endpoint, $request->toArray());

            $deliverResponse = DeliverResponse::fromResponse($response);

            if (!$deliverResponse->isSuccessful()) {
                throw DeliverException::failed(
                    $deliverResponse->getStatusCode(),
                    $deliverResponse->getMessage(),
                    $request->getTrackingCode()
                );
            }

            return $deliverResponse;
        } catch (NetworkException $e) {
            throw DeliverException::failed(
                0,
                $e->getMessage(),
                $request->getTrackingCode()
            );
        }
    }

    /**
     * Refund a payment.
     *
     * @param RefundRequest $request
     * @return RefundResponse
     * @throws RefundException
     */
    public function refund(RefundRequest $request): RefundResponse
    {
        $this->prepareClient();

        $endpoint = config('digipay.paths.refund') . '?type=' . $request->getType();

        try {
            $response = $this->httpClient->post($endpoint, $request->toArray());

            $refundResponse = RefundResponse::fromResponse($response);

            if (!$refundResponse->isSuccessful()) {
                throw RefundException::failed(
                    $refundResponse->getStatusCode(),
                    $refundResponse->getMessage(),
                    $request->getSaleTrackingCode()
                );
            }

            return $refundResponse;
        } catch (NetworkException $e) {
            throw RefundException::failed(
                0,
                $e->getMessage(),
                $request->getSaleTrackingCode()
            );
        }
    }

    /**
     * Inquire about a refund status.
     *
     * @param string $refundProviderId The providerId used when creating the refund
     * @param int $type Payment type
     * @return RefundInquiryResponse
     * @throws RefundException
     */
    public function inquireRefund(string $refundProviderId, int $type): RefundInquiryResponse
    {
        $this->prepareClient();

        $endpoint = config('digipay.paths.refund') . '/' . $refundProviderId . '?type=' . $type;

        try {
            $response = $this->httpClient->post($endpoint, []);

            $inquiryResponse = RefundInquiryResponse::fromResponse($response);

            if (!$inquiryResponse->isSuccessful()) {
                throw RefundException::inquiryFailed(
                    $refundProviderId,
                    $inquiryResponse->getMessage()
                );
            }

            return $inquiryResponse;
        } catch (NetworkException $e) {
            throw RefundException::inquiryFailed(
                $refundProviderId,
                $e->getMessage()
            );
        }
    }

    /**
     * Check if sandbox mode is enabled.
     *
     * @return bool
     */
    public function isSandbox(): bool
    {
        return $this->config->isSandbox();
    }

    /**
     * Get the base API URL based on environment.
     *
     * @return string
     */
    public function getBaseUrl(): string
    {
        return $this->config->getBaseUrl();
    }

    /**
     * Prepare the HTTP client with auth token and base URL.
     *
     * @return void
     */
    private function prepareClient(): void
    {
        $token = $this->authenticator->getAccessToken();

        $this->httpClient
            ->setBaseUrl($this->config->getBaseUrl())
            ->setTimeout($this->config->getTimeout())
            ->setBearerToken($token);
    }
}
