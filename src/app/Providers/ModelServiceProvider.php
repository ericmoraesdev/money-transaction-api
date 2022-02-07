<?php

namespace App\Providers;

use App\Models\User\User;
use App\Models\Transaction\Transaction;
use Illuminate\Support\ServiceProvider;
use App\Interfaces\User\UserModelInterface;
use App\Interfaces\Transaction\TransactionModelInterface;

class ModelServiceProvider extends ServiceProvider
{
    /**
     * Register any application models.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(UserModelInterface::class, User::class);
        $this->app->bind(TransactionModelInterface::class, Transaction::class);
    }
}
