<?php

namespace App\Entities\User;

use App\Abstracts\AbstractPersistingEntity;
use App\Exceptions\User\UserEntityException;
use App\Interfaces\User\UserEntityInterface;

class User extends AbstractPersistingEntity implements UserEntityInterface
{
    const TYPE_COMMON = 'common';
    const TYPE_SHOPKEEPER = 'shopkeeper';

    private $type;
    private $fullname;
    private $cpf;
    private $cnpj;
    private $email;
    private $password;
    private $availableMoney;

    public function __construct(array $data = [])
    {
        if (isset($data) && !empty($data)) {
            $this->populate($data);
        }
    }

    public function populate(array $data): self
    {

        if (isset($data['id'])) {
            $this->setId($data['id']);
        }

        if (!isset($data['cpf']) && !isset($data['cnpj'])) {
            throw new UserEntityException('É necessário informar o CPF ou CNPJ do usuário');
        }

        $this->setType($data['type'] ?? '');
        $this->setFullname($data['fullname'] ?? '');
        $this->setEmail($data['email'] ?? '');

        if (isset($data['cpf'])) {
            $this->setCpf($data['cpf'] ?? '');
        }

        if (isset($data['cnpj'])) {
            $this->setCnpj($data['cnpj'] ?? '');
        }

        if (isset($data['password'])) {
            $this->setPassword($data['password'] ?? '');
        }

        if (isset($data['available_money'])) {
            $this->setAvailableMoney(floatval($data['available_money']) ?? 0);
        }

        return $this;
    }

    public function setType(string $type): self
    {
        if (!in_array($type, $this->getAllowedTypes())) {
            throw new UserEntityException('Tipo de usuário inválido.');
        }

        $this->type = $type;
        return $this;
    }

    public function getAllowedTypes(): array
    {
        return [
            self::TYPE_COMMON,
            self::TYPE_SHOPKEEPER
        ];
    }

    public function getType(): ?string
    {
        return $this->type;
    }


    public function setFullname(string $fullname): self
    {
        if (strlen($fullname) < 1) {
            throw new UserEntityException('É necessário informar o nome completo do usuário');
        }

        $this->fullname = $fullname;

        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->fullname;
    }

    public function getFirstName(): ?string
    {
        $nameParts = explode(" ", $this->name ?? '');
        return $nameParts[0];
    }

    public function setEmail(string $email): self
    {
        if (strlen($email) < 1 || strlen($email) > 150 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new UserEntityException('É necessário informar um e-mail válido.');
        }

        $this->email = $email;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }


    public function setCpf(string $cpf): self
    {
        if (strlen($cpf) < 1 || strlen($cpf) > 14) {
            throw new UserEntityException('É necessário informar um CPF válido.');
        }

        $this->cpf = $cpf;

        return $this;
    }

    public function getCpf(): ?string
    {
        return $this->cpf;
    }

    public function setCnpj(string $cnpj): self
    {
        if (strlen($cnpj) < 1 || strlen($cnpj) > 18) {
            throw new UserEntityException('É necessário informar um CNPJ válido.');
        }

        $this->cnpj = $cnpj;

        return $this;
    }

    public function getCnpj(): ?string
    {
        return $this->cnpj;
    }

    public function setPassword(string $password): self
    {
        if (strlen($password) > 60 || strlen($password) < 6) {
            throw new UserEntityException('É necessário informar uma senha válida.');
        }

        $this->password = $password;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setAvailableMoney(float $availableMoney): self
    {
        if ($availableMoney < 0) {
            throw new UserEntityException('O valor disponível precisa ser maior que zero.');
        }

        $this->availableMoney = $availableMoney;

        return $this;
    }

    public function getAvailableMoney(): float
    {
        return $this->availableMoney ?? 0;
    }

    public function isAllowedToPay(): bool
    {
        return !$this->isShopkeeper();
    }

    public function isShopkeeper(): bool
    {
        return $this->type === self::TYPE_SHOPKEEPER;
    }

    public function hasEnoughMoney(float $money): bool
    {
        return $this->getAvailableMoney() >= $money;
    }

    public function increaseAvailableMoney(float $amount): self
    {
        $this->availableMoney += $amount;
        return $this;
    }

    public function decreaseAvailableMoney(float $amount): self
    {
        if (!$this->hasEnoughMoney($amount)) {
            throw new UserEntityException('Não há saldo suficiente.');
        }

        $this->availableMoney -= $amount;
        return $this;
    }

}
