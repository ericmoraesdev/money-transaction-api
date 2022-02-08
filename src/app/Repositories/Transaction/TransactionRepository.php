<?php

namespace App\Repositories\Transaction;

use App\Entities\Transaction\Transaction;
use App\Interfaces\Transaction\TransactionModelInterface;
use App\Interfaces\Transaction\TransactionEntityInterface;
use App\Abstracts\Transaction\AbstractTransactionRepository;
use App\Interfaces\Transaction\TransactionRepositoryInterface;

class TransactionRepository extends AbstractTransactionRepository implements TransactionRepositoryInterface
{
    protected $transactionEntity;
    protected $transaction;

    public function __construct(
        TransactionEntityInterface $transactionEntity,
        TransactionModelInterface $transaction
    )
    {
        $this->transactionEntity = $transactionEntity;
        $this->transaction = $transaction;
    }

    protected function model(): ?TransactionModelInterface
    {
        return $this->transaction;
    }

    public function getById(int $id): TransactionEntityInterface
    {
        $transaction = $this->getModelById($id);

        $transactionEntity = $this->transactionEntity->getNewInstance();

        return $transactionEntity->populate([
            'id' => $transaction->id,
            'payer' => $transaction->payer,
            'payee' => $transaction->payee,
            'amount' => $transaction->amount,
            'date' => $transaction->date,
            'status' => $transaction->status
        ]);
    }

    public function save(TransactionEntityInterface $transaction): TransactionEntityInterface
    {

        $transactionModel = $this->transaction;

        if ($transaction->getId() > 0) {

            $transactionModel = $this->getModelById($transaction->getId());
        }

        $transactionModel->payer_id = $transaction->getPayerId();
        $transactionModel->payee_id = $transaction->getPayeeId();
        $transactionModel->amount = $transaction->getAmount();
        $transactionModel->status = $transaction->getStatus();

        $transactionModel->save();

        if (isset($this->transaction->id)) {
            $transaction->setId($this->transaction->id);
        }

        return $transaction;
    }

    public function hasPendingTransaction(int $payerId): bool
    {
        $transaction = $this->transaction->where('payer_id', '=', $payerId)
            ->where('status', '=', Transaction::STATUS_PENDING)
            ->count();

        return $transaction > 0;
    }
}
