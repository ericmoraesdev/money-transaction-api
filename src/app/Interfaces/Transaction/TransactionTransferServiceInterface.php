<?php

namespace App\Interfaces\Transaction;

use App\Interfaces\Transaction\TransactionEntityInterface;

interface TransactionTransferServiceInterface
{
    public function transfer(TransactionEntityInterface $transaction): TransactionEntityInterface;
}
