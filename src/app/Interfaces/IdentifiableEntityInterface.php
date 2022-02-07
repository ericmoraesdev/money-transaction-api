<?php

namespace App\Interfaces;

use App\Interfaces\EntityInterface;

interface IdentifiableEntityInterface extends EntityInterface
{
    public function setId(int $id): self;
    public function getId(): ?int;
}
