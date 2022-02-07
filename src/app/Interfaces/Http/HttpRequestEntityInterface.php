<?php

namespace App\Interfaces\Http;

interface HttpRequestEntityInterface
{
    public function getUri(): string;
    public function getHeaders(): array;
    public function getPayload(): array;
}
