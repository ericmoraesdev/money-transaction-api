<?php

namespace App\Entities\Http;

use App\Interfaces\Http\HttpRequestEntityInterface;

class HttpRequest implements HttpRequestEntityInterface
{
    private $uri;
    private $headers;
    private $payload;

    public function __construct(
        string $uri,
        array $headers = [],
        array $payload = []
    ) {
        $this->uri = $uri;
        $this->headers = $headers;
        $this->payload = $payload;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getPayload(): array
    {
        return $this->payload;
    }
}
