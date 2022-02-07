<?php

namespace App\Interfaces\Notification;

use App\Interfaces\Notification\NotificationEntityInterface;

interface NotificationSendingServiceInterface
{
    public function send(NotificationEntityInterface $notification): void;
}
