<?php

namespace App\Entities;

use App\Interfaces\User\UserEntityInterface;
use App\Interfaces\Notification\NotificationEntityInterface;

class Notification implements NotificationEntityInterface
{
    private $user;
    private $title;
    private $message;

    public function setUser(UserEntityInterface $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getUser(): UserEntityInterface
    {
        return $this->user;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

}
