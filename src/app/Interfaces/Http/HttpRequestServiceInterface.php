<?php

namespace App\Interfaces\Http;

use App\Interfaces\Http\HttpRequestEntityInterface;
use App\Interfaces\Http\HttpResponseEntityInterface;

interface HttpRequestServiceInterface
{
    public function get(HttpRequestEntityInterface $request): HttpResponseEntityInterface;
    public function post(HttpRequestEntityInterface $request): HttpResponseEntityInterface;
    public function put(HttpRequestEntityInterface $request): HttpResponseEntityInterface;
    public function delete(HttpRequestEntityInterface $request): HttpResponseEntityInterface;
}
