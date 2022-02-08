<?php

namespace App\Abstracts\Transaction;

use App\Interfaces\User\UserEntityInterface;
use App\Abstracts\AbstractIdentifiableEntity;
use App\Exceptions\Transaction\TransactionEntityException;
use App\Interfaces\Transaction\TransactionEntityInterface;

abstract class AbstractTransactionEntity extends AbstractIdentifiableEntity implements TransactionEntityInterface
{
    protected $payer;
    protected $payee;
    protected $amount;
    protected $status;

    public abstract function allowedStatuses(): array;

    public function setPayer(UserEntityInterface $payer): self
    {
        $this->payer = $payer;

        return $this;
    }

    public function getPayer(): ?UserEntityInterface
    {
        return $this->payer;
    }

    public function getPayerId(): ?int
    {
        $payer = $this->getPayer();

        return isset($payer) ? $payer->getId() : null;
    }

    public function setPayee(UserEntityInterface $payee): self
    {
        $this->payee = $payee;

        return $this;
    }

    public function getPayee(): UserEntityInterface
    {
        return $this->payee;
    }

    public function getPayeeId(): ?int
    {
        $payee = $this->getPayee();

        return isset($payee) ? $payee->getId() : null;
    }


    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setStatus(string $status): self
    {
        if (!in_array($status, $this->allowedStatuses())) {
            throw new TransactionEntityException('O status da transação está inválido.');
        }

        $this->status = $status;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function transfer(): self
    {
        $this->validate();

        $this->payer->decreaseAvailableMoney($this->amount);
        $this->payee->increaseAvailableMoney($this->amount);

        $this->setDoneStatus();

        return $this;
    }

    public function validate(): self
    {
        $this->validatePayee();
        $this->validateAmount();
        $this->validatePayer();

        return $this;
    }

    public function validatePayee(): self
    {
        if (!isset($this->payee)) {
            throw new TransactionEntityException('É necessário informar o usuário a ser pago para realizar a transação.');
        }

        return $this;
    }

    public function validateAmount(): self
    {
        if ($this->amount < 1) {
            throw new TransactionEntityException('O valor da transação precisa ser maior que zero.');
        }

        return $this;
    }

    public function validatePayer(): self
    {
        if (!isset($this->payer)) {
            throw new TransactionEntityException('É necessário informar o usuário pagador para realizar a transação.');
        }

        if (!$this->payer->isAllowedToPay()) {
            throw new TransactionEntityException('O usuário pagador não está autorizado a realizar transações.');
        }

        if (!$this->payer->hasEnoughMoney($this->amount)) {
            throw new TransactionEntityException('O usuário pagador não possui saldo suficiente.');
        }

        return $this;
    }
}
