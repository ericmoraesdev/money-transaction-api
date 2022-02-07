<?php

namespace App\Interfaces\Http;

interface HttpResponseEntityInterface
{
    public function setStatusCode(int $statusCode): self;
    public function getStatusCode(): ?int;
    public function setHeaders(array $headers): self;
    public function getHeaders(): array;
    public function setBody($body): self;
    public function getBody();
    public function isServiceUnavailable(): bool;
}
