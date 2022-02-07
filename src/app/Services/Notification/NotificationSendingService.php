<?php

namespace App\Services\Notification;

use App\Entities\Http\HttpRequest;
use App\Interfaces\Http\HttpRequestServiceInterface;
use App\Interfaces\Notification\NotificationEntityInterface;
use App\Interfaces\Notification\NotificationSendingServiceInterface;
use Throwable;

class NotificationSendingService implements NotificationSendingServiceInterface
{
    const NOTIFICATION_URI = 'http://o4d9z.mocklab.io/notify';

    private $httpRequestService;

    public function __construct(HttpRequestServiceInterface $httpRequestService)
    {
        $this->httpRequestService = $httpRequestService;
    }

    public function send(NotificationEntityInterface $notification): void
    {

        $user = $notification->getUser();

        if (!isset($user)) {
            return;
        }

        $this->httpRequestService->post(new HttpRequest(
            self::NOTIFICATION_URI,
            [
                'user_id' => $user->getId(),
                'title' => $notification->getTitle(),
                'message' => $notification->getMessage(),
            ]
        ));

    }

}
