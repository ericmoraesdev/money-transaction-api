<?php

namespace App\Interfaces\Event;

use App\Interfaces\Event\EventEntityInterface;

interface EventInterface {
    public function perform(EventEntityInterface $event): void;
}
