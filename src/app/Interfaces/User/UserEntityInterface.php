<?php

namespace App\Interfaces\User;

use App\Interfaces\User\TradableEntityInterface;

interface UserEntityInterface extends TradableEntityInterface
{
    public function getType(): ?string;
    public function getAllowedTypes(): ?array;
    public function getFirstName(): ?string;
    public function getFullName(): ?string;
    public function getCpf(): ?string;
    public function getCnpj(): ?string;
    public function getEmail(): ?string;
    public function getPassword(): ?string;
    public function getAvailableMoney(): float;
}
