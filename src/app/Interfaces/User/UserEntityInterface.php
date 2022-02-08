<?php

namespace App\Interfaces\User;

use App\Interfaces\User\TradableEntityInterface;

interface UserEntityInterface extends TradableEntityInterface
{
    public function getType(): ?string;
    public function setType(string $type): self;
    public function getAllowedTypes(): ?array;
    public function getFirstName(): ?string;
    public function getFullName(): ?string;
    public function setFullName(string $fullname): self;
    public function getCpf(): ?string;
    public function setCpf(string $cpf): self;
    public function getCnpj(): ?string;
    public function setCnpj(string $cnpj): self;
    public function getEmail(): ?string;
    public function setEmail(string $email): self;
    public function getPassword(): ?string;
    public function setPassword(string $password): self;
    public function getAvailableMoney(): float;
}
