<?php

namespace App\Interfaces\User;

use App\Interfaces\PersistingEntityInterface;

interface TradableEntityInterface extends PersistingEntityInterface
{
    public function isAllowedToPay(): bool;
    public function hasEnoughMoney(float $money): bool;
    public function increaseAvailableMoney(float $amount): self;
    public function decreaseAvailableMoney(float $amount): self;
}
