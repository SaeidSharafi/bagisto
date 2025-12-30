<?php

declare(strict_types=1);

namespace DigipayGateway\Contracts;

use DigipayGateway\DataTransferObjects\TokenResponse;
use DigipayGateway\Exceptions\AuthenticationException;

interface AuthenticatorInterface
{
    /**
     * Get a valid access token (from cache or fresh).
     *
     * @return string
     * @throws AuthenticationException
     */
    public function getAccessToken(): string;

    /**
     * Force refresh the access token.
     *
     * @return TokenResponse
     * @throws AuthenticationException
     */
    public function refreshToken(): TokenResponse;

    /**
     * Clear cached token.
     *
     * @return void
     */
    public function clearToken(): void;

    /**
     * Check if we have a valid cached token.
     *
     * @return bool
     */
    public function hasValidToken(): bool;
}
