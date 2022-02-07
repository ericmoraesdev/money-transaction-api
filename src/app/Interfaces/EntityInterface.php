<?php

namespace App\Interfaces;

interface EntityInterface
{
    public function populate(array $data): self;
}
