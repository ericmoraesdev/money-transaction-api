<?php

namespace App\Providers;

use App\Entities\User\User;
use App\Entities\Notification;
use App\Entities\Http\HttpClient;
use App\Entities\Http\HttpRequest;
use App\Entities\Http\HttpResponse;
use Illuminate\Support\ServiceProvider;
use App\Entities\Transaction\Transaction;
use App\Interfaces\User\UserEntityInterface;
use App\Interfaces\Http\HttpClientEntityInterface;
use App\Interfaces\Http\HttpRequestEntityInterface;
use App\Interfaces\Http\HttpResponseEntityInterface;
use App\Interfaces\Transaction\TransactionEntityInterface;
use App\Interfaces\Notification\NotificationEntityInterface;

class EntityServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserEntityInterface::class, User::class);
        $this->app->bind(TransactionEntityInterface::class, Transaction::class);
        $this->app->bind(HttpClientEntityInterface::class, HttpClient::class);
        $this->app->bind(HttpRequestEntityInterface::class, HttpRequest::class);
        $this->app->bind(HttpResponseEntityInterface::class, HttpResponse::class);
        $this->app->bind(NotificationEntityInterface::class, Notification::class);
    }
}
