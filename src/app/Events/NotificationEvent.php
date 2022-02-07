<?php

namespace App\Events;

use App\Interfaces\Notification\NotificationEntityInterface;

class NotificationEvent
{
    public $notification;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(NotificationEntityInterface $notification)
    {
        $this->notification = $notification;
    }

    public function getNotification(): NotificationEntityInterface
    {
        return $this->notification;
    }

}
