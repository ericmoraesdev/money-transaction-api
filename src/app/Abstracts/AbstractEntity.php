<?php
namespace App\Abstracts;

use App\Interfaces\EntityInterface;

abstract class AbstractEntity implements EntityInterface
{
    public function getNewInstance(): self
    {
        return new $this;
    }
}
