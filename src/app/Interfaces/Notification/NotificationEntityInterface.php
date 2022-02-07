<?php

namespace App\Interfaces\Notification;

use App\Interfaces\User\UserEntityInterface;
use App\Interfaces\Event\EventEntityInterface;

interface NotificationEntityInterface extends EventEntityInterface
{
    public function setUser(UserEntityInterface $user): self;
    public function getUser(): UserEntityInterface;
    public function setTitle(string $title): self;
    public function getTitle(): string;
    public function setMessage(string $message): self;
    public function getMessage(): string;

}
