<?php

namespace App\Interfaces\Transaction;

use App\Interfaces\Transaction\TransactionEntityInterface;

interface TransactionRepositoryInterface
{
    public function getById(int $id): ?TransactionEntityInterface;
    public function save(TransactionEntityInterface $entity): TransactionEntityInterface;
    public function insert(TransactionEntityInterface $entity): TransactionEntityInterface;
    public function update(TransactionEntityInterface $entity): TransactionEntityInterface;
    public function delete(TransactionEntityInterface $entity): TransactionEntityInterface;
    public function deleteById(int $id): void;
}
