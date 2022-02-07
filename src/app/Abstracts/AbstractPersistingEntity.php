<?php

namespace App\Abstracts;

use App\Abstracts\AbstractIdentifiableEntity;
use App\Interfaces\PersistingEntityInterface;

abstract class AbstractPersistingEntity extends AbstractIdentifiableEntity implements PersistingEntityInterface
{
    protected $createdAt;
    protected $updatedAt;

    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }
}
