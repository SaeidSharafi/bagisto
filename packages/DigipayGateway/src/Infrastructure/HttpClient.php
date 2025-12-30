<?php

declare(strict_types=1);

namespace DigipayGateway\Infrastructure;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\GuzzleException;
use DigipayGateway\Exceptions\NetworkException;
use Illuminate\Support\Facades\Log;

class HttpClient
{
    private Client $client;
    private string $baseUrl = '';
    private int $timeout = 30;
    private ?string $bearerToken = null;
    private bool $loggingEnabled;

    public function __construct()
    {
        $this->client = new Client();
        $this->loggingEnabled = config('digipay.logging.enabled', true);
    }

    /**
     * Send a POST request with JSON body.
     *
     * @param string $endpoint
     * @param array<string, mixed> $data
     * @param array<string, string> $headers
     * @return array<string, mixed>
     * @throws NetworkException
     */
    public function post(string $endpoint, array $data = [], array $headers = []): array
    {
        return $this->request('POST', $endpoint, [
            'json' => $data,
            'headers' => $this->buildHeaders($headers),
        ]);
    }

    /**
     * Send a POST request with form data (for OAuth).
     *
     * @param string $endpoint
     * @param array<string, mixed> $data
     * @param array<string, string> $headers
     * @return array<string, mixed>
     * @throws NetworkException
     */
    public function postForm(string $endpoint, array $data = [], array $headers = []): array
    {
        return $this->request('POST', $endpoint, [
            'form_params' => $data,
            'headers' => array_merge([
                'Content-Type' => 'application/x-www-form-urlencoded',
            ], $headers),
        ]);
    }

    /**
     * Set the base URL for all requests.
     *
     * @param string $url
     * @return self
     */
    public function setBaseUrl(string $url): self
    {
        $this->baseUrl = rtrim($url, '/');
        return $this;
    }

    /**
     * Set request timeout in seconds.
     *
     * @param int $seconds
     * @return self
     */
    public function setTimeout(int $seconds): self
    {
        $this->timeout = $seconds;
        return $this;
    }

    /**
     * Set bearer token for authentication.
     *
     * @param string $token
     * @return self
     */
    public function setBearerToken(string $token): self
    {
        $this->bearerToken = $token;
        return $this;
    }

    /**
     * Execute HTTP request.
     *
     * @param string $method
     * @param string $endpoint
     * @param array<string, mixed> $options
     * @return array<string, mixed>
     * @throws NetworkException
     */
    private function request(string $method, string $endpoint, array $options): array
    {
        $url = $this->baseUrl . $endpoint;

        $options['timeout'] = $this->timeout;
        $options['http_errors'] = false;

        $this->logRequest($method, $url, $options);

        try {
            $response = $this->client->request($method, $url, $options);
            $statusCode = $response->getStatusCode();
            $body = $response->getBody()->getContents();

            $this->logResponse($url, $statusCode, $body);

            // Parse JSON response
            $data = json_decode($body, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw NetworkException::invalidResponse($url, 'Invalid JSON response');
            }

            // Check for HTTP errors
            if ($statusCode >= 400) {
                throw NetworkException::httpError($url, $statusCode, $body);
            }

            return $data;
        } catch (ConnectException $e) {
            $this->logError($url, $e);
            throw NetworkException::connectionFailed($url, $e->getMessage());
        } catch (RequestException $e) {
            $this->logError($url, $e);

            if ($e->hasResponse()) {
                $statusCode = $e->getResponse()->getStatusCode();
                $body = $e->getResponse()->getBody()->getContents();
                throw NetworkException::httpError($url, $statusCode, $body);
            }

            throw NetworkException::connectionFailed($url, $e->getMessage());
        } catch (GuzzleException $e) {
            $this->logError($url, $e);
            throw NetworkException::connectionFailed($url, $e->getMessage());
        }
    }

    /**
     * Build request headers.
     *
     * @param array<string, string> $additional
     * @return array<string, string>
     */
    private function buildHeaders(array $additional = []): array
    {
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Agent' => 'WEB',
            'Digipay-Version' => config('digipay.default_api_version', '2022-02-02'),
        ];

        if ($this->bearerToken) {
            $headers['Authorization'] = 'Bearer ' . $this->bearerToken;
        }

        return array_merge($headers, $additional);
    }

    /**
     * Log outgoing request.
     *
     * @param string $method
     * @param string $url
     * @param array<string, mixed> $options
     * @return void
     */
    private function logRequest(string $method, string $url, array $options): void
    {
        if (!$this->loggingEnabled) {
            return;
        }

        $logData = [
            'method' => $method,
            'url' => $url,
        ];

        // Mask sensitive data
        if (isset($options['json'])) {
            $logData['body'] = $this->maskSensitiveData($options['json']);
        }
        if (isset($options['form_params'])) {
            $logData['form_params'] = $this->maskSensitiveData($options['form_params']);
        }

        Log::channel(config('digipay.logging.channel', 'stack'))
            ->info('[Digipay] Request', $logData);
    }

    /**
     * Log response.
     *
     * @param string $url
     * @param int $statusCode
     * @param string $body
     * @return void
     */
    private function logResponse(string $url, int $statusCode, string $body): void
    {
        if (!$this->loggingEnabled) {
            return;
        }

        $data = json_decode($body, true);

        Log::channel(config('digipay.logging.channel', 'stack'))
            ->info('[Digipay] Response', [
                'url' => $url,
                'status_code' => $statusCode,
                'body' => $data ? $this->maskSensitiveData($data) : $body,
            ]);
    }

    /**
     * Log error.
     *
     * @param string $url
     * @param \Throwable $e
     * @return void
     */
    private function logError(string $url, \Throwable $e): void
    {
        Log::channel(config('digipay.logging.channel', 'stack'))
            ->error('[Digipay] Error', [
                'url' => $url,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
    }

    /**
     * Mask sensitive fields in data.
     *
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    private function maskSensitiveData(array $data): array
    {
        $sensitiveFields = config('digipay.logging.sensitive_fields', []);

        foreach ($sensitiveFields as $field) {
            if (isset($data[$field])) {
                $data[$field] = '***MASKED***';
            }
        }

        return $data;
    }
}
