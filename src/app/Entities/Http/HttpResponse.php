<?php

namespace App\Entities\Http;

use App\Interfaces\Http\HttpResponseEntityInterface;
use Illuminate\Http\Response;

class HttpResponse implements HttpResponseEntityInterface
{
    private $statusCode;
    private $headers;
    private $body;

    public function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function getStatusCode(): ?int
    {
        return $this->statusCode;
    }

    public function setHeaders(array $headers): self
    {
        $this->headers = $headers;
        return $this;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param mixed $body
     */
    public function setBody($body): self
    {
        $this->body = $body;
        return $this;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function isServiceUnavailable(): bool
    {
        return in_array($this->statusCode, $this->getServiceProblemStatuses());
    }

    public function getServiceProblemStatuses(): array
    {
        return [
            Response::HTTP_SERVICE_UNAVAILABLE,
            Response::HTTP_INTERNAL_SERVER_ERROR,
            Response::HTTP_NOT_IMPLEMENTED,
            Response::HTTP_BAD_GATEWAY,
            Response::HTTP_GATEWAY_TIMEOUT,
            Response::HTTP_VERSION_NOT_SUPPORTED,
            Response::HTTP_VARIANT_ALSO_NEGOTIATES_EXPERIMENTAL,
            Response::HTTP_INSUFFICIENT_STORAGE,
            Response::HTTP_LOOP_DETECTED,
            Response::HTTP_NOT_EXTENDED,
            Response::HTTP_NETWORK_AUTHENTICATION_REQUIRED
        ];
    }
}
