<?php

namespace App\Services\Notification;

use App\Events\NotificationEvent;
use App\Interfaces\Notification\NotificationEntityInterface;
use App\Interfaces\Notification\NotificationServiceInterface;

class NotificationService implements NotificationServiceInterface
{

    public function notify(NotificationEntityInterface $notification): void
    {
        event(new NotificationEvent($notification));
    }

}
