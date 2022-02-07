<?php

namespace App\Abstracts\Transaction;

use App\Abstracts\AbstractRepository;
use App\Interfaces\Transaction\TransactionEntityInterface;

abstract class AbstractTransactionRepository extends AbstractRepository
{

    public abstract function save(TransactionEntityInterface $transaction): TransactionEntityInterface;

    public function insert(TransactionEntityInterface $transaction): TransactionEntityInterface
    {
        return $this->save($transaction);
    }

    public function update(TransactionEntityInterface $transaction): TransactionEntityInterface
    {
        return $this->save($transaction);
    }

    public function delete(TransactionEntityInterface $transaction): TransactionEntityInterface
    {
        $transactionModel = $this->getModelById($transaction->getId());
        $transactionModel->delete();

        return $transaction;
    }
}
