<?php

namespace App\Models\Transaction;

use App\Abstracts\AbstractModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Interfaces\Transaction\TransactionModelInterface;

class Transaction extends AbstractModel implements TransactionModelInterface
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'payer_id',
        'payee_id',
        'amount',
        'status'
    ];
}
