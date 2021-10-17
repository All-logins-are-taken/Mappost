<?php

declare(strict_types=1);

namespace App\Http;

use CurlHandle;

class HttpClient
{
    public const POST = 'POST';
    public const GET = 'GET';

    private CurlHandle $handle;
    private int $httpCode;
    private bool|string $response;

    public function get(string $url, $header = []): bool|string
    {
        $this->handle = curl_init();
        curl_setopt($this->handle, CURLOPT_URL, $url);
        curl_setopt($this->handle, CURLOPT_HTTPHEADER, $header);
        curl_setopt($this->handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->handle, CURLOPT_CUSTOMREQUEST, self::GET);
        $this->response = curl_exec($this->handle);
        $this->httpCode = curl_getinfo($this->handle, CURLINFO_HTTP_CODE);
        curl_close($this->handle);

        return $this->response;
    }

    public function post(string $url, array $header, string $body): bool|string
    {
        $this->handle = curl_init();
        curl_setopt($this->handle, CURLOPT_URL, $url);
        curl_setopt($this->handle, CURLOPT_HTTPHEADER, $header);
        curl_setopt($this->handle, CURLOPT_TIMEOUT, 30);
        curl_setopt($this->handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->handle, CURLOPT_CUSTOMREQUEST, self::POST);
        curl_setopt($this->handle, CURLOPT_POSTFIELDS, $body);
        $this->response = curl_exec($this->handle);
        $this->httpCode = curl_getinfo($this->handle, CURLINFO_HTTP_CODE);
        curl_close($this->handle);

        return $this->response;
    }

    public function getResponse(): bool|string
    {
        return $this->response;
    }

    public function getHttpCode(): int
    {
        return $this->httpCode;
    }
}
