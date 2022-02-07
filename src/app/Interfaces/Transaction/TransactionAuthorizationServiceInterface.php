<?php

namespace App\Interfaces\Transaction;

use App\Interfaces\Transaction\TransactionEntityInterface;

interface TransactionAuthorizationServiceInterface
{
    /**
     * Returns the approved or rejected transaction entity
     *
     * @param TransactionEntityInterface $transaction
     * @return TransactionEntityInterface
     */
    public function authorize(TransactionEntityInterface $transaction): TransactionEntityInterface;
}
