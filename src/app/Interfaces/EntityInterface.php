<?php

namespace App\Interfaces;

interface EntityInterface
{
    public function populate(array $data): self;
    public function getNewInstance(): self;
}
