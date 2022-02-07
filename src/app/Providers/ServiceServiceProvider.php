<?php

namespace App\Providers;


use Illuminate\Support\ServiceProvider;
use App\Services\Http\HttpRequestService;
use App\Services\Transaction\TransactionService;
use App\Services\Notification\NotificationService;
use App\Interfaces\Http\HttpRequestServiceInterface;
use App\Services\Transaction\TransactionTransferService;
use App\Services\Notification\NotificationSendingService;
use App\Interfaces\Transaction\TransactionServiceInterface;
use App\Interfaces\Notification\NotificationServiceInterface;
use App\Services\Transaction\TransactionAuthorizationService;
use App\Interfaces\Transaction\TransactionTransferServiceInterface;
use App\Interfaces\Notification\NotificationSendingServiceInterface;
use App\Interfaces\Transaction\TransactionAuthorizationServiceInterface;

class ServiceServiceProvider extends ServiceProvider
{
    /**
     * Register any services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(TransactionServiceInterface::class, TransactionService::class);
        $this->app->bind(TransactionTransferServiceInterface::class, TransactionTransferService::class);
        $this->app->bind(TransactionAuthorizationServiceInterface::class, TransactionAuthorizationService::class);
        $this->app->bind(HttpRequestServiceInterface::class, HttpRequestService::class);
        $this->app->bind(NotificationServiceInterface::class, NotificationService::class);
        $this->app->bind(NotificationSendingServiceInterface::class, NotificationSendingService::class);
    }
}
