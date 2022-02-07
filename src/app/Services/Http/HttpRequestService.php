<?php

namespace App\Services\Http;

use App\Interfaces\Http\HttpClientEntityInterface;
use App\Interfaces\Http\HttpRequestEntityInterface;
use App\Interfaces\Http\HttpRequestServiceInterface;
use App\Interfaces\Http\HttpResponseEntityInterface;

class HttpRequestService implements HttpRequestServiceInterface
{
    public function __construct(HttpClientEntityInterface $client)
    {
        $this->client = $client;
    }

    public function get(HttpRequestEntityInterface $request): HttpResponseEntityInterface
    {
        return $this->client->request('GET', $request->getUri(), [
            'headers' => $request->getHeaders(),
        ]);
    }

    public function post(HttpRequestEntityInterface $request): HttpResponseEntityInterface
    {
        return $this->client->request('POST', $request->getUri(), [
            'headers' => $request->getHeaders(),
            'body' => $request->getPayload(),
        ]);
    }

    public function put(HttpRequestEntityInterface $request): HttpResponseEntityInterface
    {
        return $this->client->request('PUT', $request->getUri(), [
            'headers' => $request->getHeaders(),
            'body' => $request->getPayload(),
        ]);
    }

    public function delete(HttpRequestEntityInterface $request): HttpResponseEntityInterface
    {
        return $this->client->request('DELETE', $request->getUri(), [
            'headers' => $request->getHeaders(),
            'body' => $request->getPayload(),
        ]);
    }


}
