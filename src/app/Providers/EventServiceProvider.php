<?php

namespace App\Providers;

use App\Events\NotificationEvent;
use App\Listeners\NotificationListener;
use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        NotificationEvent::class => [
            NotificationListener::class,
        ],
    ];
}
