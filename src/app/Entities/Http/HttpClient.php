<?php

namespace App\Entities\Http;

use GuzzleHttp\Client;
use App\Interfaces\Http\HttpClientEntityInterface;
use App\Interfaces\Http\HttpResponseEntityInterface;

class HttpClient implements HttpClientEntityInterface
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function  request(string $method, string $uri, array $options = []): HttpResponseEntityInterface
    {

        if (isset($options['body']) && is_array($options['body'])) {
            $options['form_params'] = $options['body'];
            unset($options['body']);
        }

        $response = $this->client->request($method, $uri, $options);

        return (new HttpResponse())
        ->setStatusCode(
            $response->getStatusCode()
        )
        ->setHeaders(
            $response->getHeaders()
        )
        ->setBody(
            $response->getBody()
        );

    }
}
