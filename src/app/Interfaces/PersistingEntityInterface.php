<?php

namespace App\Interfaces;

use DateTime;
use App\Interfaces\IdentifiableEntityInterface;

interface PersistingEntityInterface extends IdentifiableEntityInterface
{
    public function setCreatedAt(DateTime $date): self;
    public function getCreatedAt(): ?DateTime;
    public function setUpdatedAt(DateTime $date): self;
    public function getUpdatedAt(): ?DateTime;

}
