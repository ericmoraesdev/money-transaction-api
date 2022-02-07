<?php

namespace App\Entities\Transaction;

use App\Interfaces\User\TradableEntityInterface;
use App\Abstracts\Transaction\AbstractTransactionEntity;
use App\Exceptions\Transaction\TransactionEntityException;
use App\Interfaces\Transaction\TransactionEntityInterface;

class Transaction extends AbstractTransactionEntity implements TransactionEntityInterface
{
    const STATUS_PENDING = 'pending';
    const STATUS_AUTHORIZED = 'authorized';
    const STATUS_UNAUTHORIZED = 'unauthorized';
    const STATUS_FAILED = 'failed';
    const STATUS_DONE = 'done';
    const STATUS_REVERSED = 'reversed';

    public function __construct(array $data = [])
    {
        if (!empty($data)) {
            $this->populate($data);
        }
    }

    public function populate(array $data): self
    {
        if (isset($data['id'])) {
            $this->setId($data['id']);
        }

        $this->setPayer($data['payer'] ?? null);
        $this->setPayee($data['payee'] ?? null);
        $this->setAmount((float)$data['amount'] ?? 0);
        $this->setStatus((string)$data['status'] ?? '');

        return $this;
    }

    public function setId(int $id): self
    {
        if ($id < 1) {
            throw new TransactionEntityException('O código identificador da transação precisa ser maior que zero.');
        }

        return parent::setId($id);
    }

    public function setPayer(TradableEntityInterface $payer): self
    {

        if (!$payer->isAllowedToPay()) {
            throw new TransactionEntityException('Este usuário não pode realizar essa transação.');
        }

        return parent::setPayer($payer);
    }

    public function setAmount(float $amount): self
    {
        if ($amount < 1) {
            throw new TransactionEntityException('O valor da transação precisa ser maior que zero.');
        }

        if (!isset($this->payer)) {
            throw new TransactionEntityException('É necessário informar o pagador antes de informar o valor');
        }

        if (!$this->payer->hasEnoughMoney($amount)) {
            throw new TransactionEntityException('O usuário pagador não possui saldo suficiente.');
        }

        return parent::setAmount($amount);
    }

    public function setPendingStatus(): self
    {
        $this->status = self::STATUS_PENDING;
        return $this;
    }

    public function setAuthorizedStatus(): self
    {
        $this->status = self::STATUS_AUTHORIZED;
        return $this;
    }

    public function setUnauthorizedStatus(): self
    {
        $this->status = self::STATUS_UNAUTHORIZED;
        return $this;
    }

    public function setFailedStatus(): self
    {
        $this->status = self::STATUS_FAILED;
        return $this;
    }

    public function setDoneStatus(): self
    {
        $this->status = self::STATUS_DONE;
        return $this;
    }

    public function setReversedStatus(): self
    {
        $this->status = self::STATUS_REVERSED;
        return $this;
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isAuthorized(): bool
    {
        return $this->status === self::STATUS_AUTHORIZED;
    }

    public function isUnauthorized(): bool
    {
        return $this->status === self::STATUS_UNAUTHORIZED;
    }

    public function isFailed(): bool
    {
        return $this->status === self::STATUS_FAILED;
    }

    public function isDone(): bool
    {
        return $this->status === self::STATUS_DONE;
    }

    public function isReversed(): bool
    {
        return $this->status === self::STATUS_REVERSED;
    }

    public function allowedStatuses(): array
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_AUTHORIZED,
            self::STATUS_UNAUTHORIZED,
            self::STATUS_FAILED,
            self::STATUS_REVERSED
        ];
    }
}
