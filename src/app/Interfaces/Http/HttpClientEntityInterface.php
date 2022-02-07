<?php

namespace App\Interfaces\Http;

use App\Interfaces\Http\HttpResponseEntityInterface;

interface HttpClientEntityInterface
{
    public function request(string $method, string $uri, array $options = []): HttpResponseEntityInterface;
}
