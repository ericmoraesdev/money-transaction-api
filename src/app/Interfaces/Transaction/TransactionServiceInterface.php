<?php

namespace App\Interfaces\Transaction;

use App\Interfaces\Transaction\TransactionEntityInterface;

interface TransactionServiceInterface
{
    public function transact(int $payerId, int $payeeId, float $amount): TransactionEntityInterface;
}
