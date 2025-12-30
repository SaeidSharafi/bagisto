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
use DigipayGateway\Exceptions\TokenException;
use DigipayGateway\Exceptions\VerificationException;
use DigipayGateway\Exceptions\ReverseException;
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
