<?php

namespace App\Interfaces\Transaction;

use App\Interfaces\User\UserEntityInterface;
use App\Interfaces\IdentifiableEntityInterface;

interface TransactionEntityInterface extends IdentifiableEntityInterface
{
    public function setPayer(UserEntityInterface $payer): self;
    public function getPayer(): ?UserEntityInterface;
    public function getPayerId(): ?int;
    public function setPayee(UserEntityInterface $payee): self;
    public function getPayee(): ?UserEntityInterface;
    public function getPayeeId(): ?int;
    public function setAmount(float $amount): self;
    public function getAmount(): ?float;
    public function setStatus(string $status): self;
    public function getStatus(): ?string;
    public function setPendingStatus(): self;
    public function setAuthorizedStatus(): self;
    public function setUnauthorizedStatus(): self;
    public function setDoneStatus(): self;
    public function setFailedStatus(): self;
    public function setReversedStatus(): self;
    public function isPending(): bool;
    public function isAuthorized(): bool;
    public function isUnauthorized(): bool;
    public function isDone(): bool;
    public function isFailed(): bool;
    public function isReversed(): bool;
    public function validate(): self;
    public function transfer(): self;
}
