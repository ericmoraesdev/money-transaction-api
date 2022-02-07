<?php

namespace App\Interfaces\Notification;

use App\Interfaces\Event\EventInterface;
use App\Interfaces\Notification\NotificationEntityInterface;

interface NotificationEventInterface extends EventInterface
{
    public function getNotification(): NotificationEntityInterface;
}
