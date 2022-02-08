<?php

namespace App\Abstracts;

use App\Interfaces\IdentifiableEntityInterface;

abstract class AbstractIdentifiableEntity extends AbstractEntity implements IdentifiableEntityInterface
{
    protected $id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }
}
