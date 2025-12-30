<?php

declare(strict_types=1);

namespace DigipayGateway\DataTransferObjects;

class TokenResponse
{
    private string $accessToken;
    private string $refreshToken;
    private string $tokenType;
    private int $expiresIn;

    public function __construct(
        string $accessToken,
        string $refreshToken,
        string $tokenType,
        int $expiresIn
    ) {
        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
        $this->tokenType = $tokenType;
        $this->expiresIn = $expiresIn;
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    public function getTokenType(): string
    {
        return $this->tokenType;
    }

    public function getExpiresIn(): int
    {
        return $this->expiresIn;
    }

    /**
     * Create from API response.
     *
     * @param array<string, mixed> $response
     * @return self
     */
    public static function fromResponse(array $response): self
    {
        return new self(
            $response['access_token'] ?? '',
            $response['refresh_token'] ?? '',
            $response['token_type'] ?? 'bearer',
            (int) ($response['expires_in'] ?? 3600)
        );
    }
}
