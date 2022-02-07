<?php

namespace App\Listeners;

use App\Events\NotificationEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Services\Notification\NotificationSendingService;
use Illuminate\Support\Facades\Log;

class NotificationListener implements ShouldQueue
{

    public $connection = 'database';

    private $notificationSendingService;

    public $tries = 3;

    public function __construct(
        NotificationSendingService $notificationSendingService
    ) {
        $this->notificationSendingService = $notificationSendingService;
    }

    /**
     * Handle the event.
     *
     * @param  NotificationEvent  $event
     * @return void
     */
    public function handle(NotificationEvent $notificationEvent): void
    {
        Log::info('Enviando notificação...');

        $this->notificationSendingService->send($notificationEvent->notification);

        Log::info('Notificação enviada!');
    }
}
