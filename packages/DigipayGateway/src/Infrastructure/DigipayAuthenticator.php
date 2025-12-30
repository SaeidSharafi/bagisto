<?php

declare(strict_types=1);

namespace DigipayGateway\Infrastructure;

use DigipayGateway\Contracts\AuthenticatorInterface;
use DigipayGateway\DataTransferObjects\TokenResponse;
use DigipayGateway\Exceptions\AuthenticationException;
use DigipayGateway\Exceptions\NetworkException;
use Illuminate\Support\Facades\Cache;

class DigipayAuthenticator implements AuthenticatorInterface
{
    private HttpClient $httpClient;
    private ConfigRepository $config;

    private const CACHE_KEY = 'digipay_access_token';
    private const REFRESH_CACHE_KEY = 'digipay_refresh_token';

    public function __construct(
        HttpClient $httpClient,
        ConfigRepository $config
    ) {
        $this->httpClient = $httpClient;
        $this->config = $config;
    }

    /**
     * Get a valid access token (from cache or fresh).
     *
     * @return string
     * @throws AuthenticationException
     */
    public function getAccessToken(): string
    {
        // Check cache first
        if ($this->hasValidToken()) {
            return Cache::get(self::CACHE_KEY);
        }

        // Try to refresh if we have a refresh token
        $refreshToken = Cache::get(self::REFRESH_CACHE_KEY);
        if ($refreshToken) {
            try {
                $response = $this->doRefreshToken($refreshToken);
                $this->cacheToken($response);
                return $response->getAccessToken();
            } catch (\Exception $e) {
                // Refresh failed, get new token
            }
        }

        // Get new token
        $response = $this->refreshToken();
        return $response->getAccessToken();
    }

    /**
     * Force refresh the access token (get new token with credentials).
     *
     * @return TokenResponse
     * @throws AuthenticationException
     */
    public function refreshToken(): TokenResponse
    {
        $this->httpClient->setBaseUrl($this->config->getBaseUrl());

        $credentials = base64_encode(
            $this->config->getClientId() . ':' . $this->config->getClientSecret()
        );

        try {
            $response = $this->httpClient->postForm(
                config('digipay.paths.oauth_token'),
                [
                    'username' => $this->config->getUsername(),
                    'password' => $this->config->getPassword(),
                    'grant_type' => 'password',
                ],
                [
                    'Authorization' => 'Basic ' . $credentials,
                ]
            );

            if (!isset($response['access_token'])) {
                throw AuthenticationException::invalidCredentials();
            }

            $tokenResponse = TokenResponse::fromResponse($response);
            $this->cacheToken($tokenResponse);

            return $tokenResponse;
        } catch (NetworkException $e) {
            throw AuthenticationException::invalidCredentials();
        }
    }

    /**
     * Clear cached token.
     *
     * @return void
     */
    public function clearToken(): void
    {
        Cache::forget(self::CACHE_KEY);
        Cache::forget(self::REFRESH_CACHE_KEY);
    }

    /**
     * Check if we have a valid cached token.
     *
     * @return bool
     */
    public function hasValidToken(): bool
    {
        return Cache::has(self::CACHE_KEY);
    }

    /**
     * Refresh token using refresh_token grant.
     *
     * @param string $refreshToken
     * @return TokenResponse
     * @throws AuthenticationException
     */
    private function doRefreshToken(string $refreshToken): TokenResponse
    {
        $this->httpClient->setBaseUrl($this->config->getBaseUrl());

        $credentials = base64_encode(
            $this->config->getClientId() . ':' . $this->config->getClientSecret()
        );

        try {
            $response = $this->httpClient->postForm(
                config('digipay.paths.oauth_token'),
                [
                    'refresh_token' => $refreshToken,
                    'grant_type' => 'refresh_token',
                ],
                [
                    'Authorization' => 'Basic ' . $credentials,
                ]
            );

            if (!isset($response['access_token'])) {
                throw AuthenticationException::tokenRefreshFailed('No access token in response');
            }

            return TokenResponse::fromResponse($response);
        } catch (NetworkException $e) {
            throw AuthenticationException::tokenRefreshFailed($e->getMessage());
        }
    }

    /**
     * Cache the token with appropriate TTL.
     *
     * @param TokenResponse $token
     * @return void
     */
    private function cacheToken(TokenResponse $token): void
    {
        $buffer = config('digipay.token_cache.buffer', 300);
        $ttl = max(1, $token->getExpiresIn() - $buffer);

        Cache::put(self::CACHE_KEY, $token->getAccessToken(), $ttl);

        if ($token->getRefreshToken()) {
            // Cache refresh token for longer (1 day)
            Cache::put(self::REFRESH_CACHE_KEY, $token->getRefreshToken(), 86400);
        }
    }
}
