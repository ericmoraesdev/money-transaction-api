<?php

namespace App\Interfaces\Notification;

use App\Interfaces\Notification\NotificationEntityInterface;

interface NotificationServiceInterface
{
    public function notify(NotificationEntityInterface $notification): void;
}
