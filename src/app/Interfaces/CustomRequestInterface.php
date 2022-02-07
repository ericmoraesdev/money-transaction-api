<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface CustomRequestInterface
{
    public function validate(): Request;
}
