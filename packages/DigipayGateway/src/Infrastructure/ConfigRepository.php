<?php

declare(strict_types=1);

namespace DigipayGateway\Infrastructure;

use DigipayGateway\Exceptions\ConfigurationException;

class ConfigRepository
{
    private const CONFIG_PREFIX = 'sales.paymentmethods.digipay.';

    /**
     * Get OAuth client ID.
     *
     * @return string
     * @throws ConfigurationException
     */
    public function getClientId(): string
    {
        $value = $this->getConfigData('client_id');

        if (empty($value)) {
            throw ConfigurationException::missingField('client_id');
        }

        return $value;
    }

    /**
     * Get OAuth client secret.
     *
     * @return string
     * @throws ConfigurationException
     */
    public function getClientSecret(): string
    {
        $value = $this->getConfigData('client_secret');

        if (empty($value)) {
            throw ConfigurationException::missingField('client_secret');
        }

        return $value;
    }

    /**
     * Get OAuth username.
     *
     * @return string
     * @throws ConfigurationException
     */
    public function getUsername(): string
    {
        $value = $this->getConfigData('username');

        if (empty($value)) {
            throw ConfigurationException::missingField('username');
        }

        return $value;
    }

    /**
     * Get OAuth password.
     *
     * @return string
     * @throws ConfigurationException
     */
    public function getPassword(): string
    {
        $value = $this->getConfigData('password');

        if (empty($value)) {
            throw ConfigurationException::missingField('password');
        }

        return $value;
    }

    /**
     * Check if sandbox mode is enabled.
     *
     * @return bool
     */
    public function isSandbox(): bool
    {
        return (bool) $this->getConfigData('sandbox');
    }

    /**
     * Get the API version header value.
     *
     * @return string
     */
    public function getApiVersion(): string
    {
        $version = $this->getConfigData('api_version');

        return $version ?: config('digipay.default_api_version', '2022-02-02');
    }

    /**
     * Get the callback URL for payment notifications.
     *
     * @return string
     */
    public function getCallbackUrl(): string
    {
        return route('digipay.callback');
    }

    /**
     * Get request timeout in seconds.
     *
     * @return int
     */
    public function getTimeout(): int
    {
        return (int) config('digipay.timeout', 30);
    }

    /**
     * Check if the payment method is active.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return (bool) $this->getConfigData('active');
    }

    /**
     * Get any config value by key.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return $this->getConfigData($key) ?? $default;
    }

    /**
     * Get base URL based on environment.
     *
     * @return string
     */
    public function getBaseUrl(): string
    {
        $env = $this->isSandbox() ? 'sandbox' : 'production';

        return config("digipay.endpoints.{$env}.base_url");
    }

    /**
     * Get web URL based on environment.
     *
     * @return string
     */
    public function getWebUrl(): string
    {
        $env = $this->isSandbox() ? 'sandbox' : 'production';

        return config("digipay.endpoints.{$env}.web_url");
    }

    /**
     * Get config data from Bagisto's core config.
     *
     * @param string $field
     * @return mixed
     */
    protected function getConfigData(string $field)
    {
        return core()->getConfigData(self::CONFIG_PREFIX . $field);
    }
}
