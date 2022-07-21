<?php

namespace App\Message;

use App\Service\ApiService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class ApiNotificationHandler implements MessageHandlerInterface
{
    public function __construct(
        private readonly ApiService $service,
    ){}


    public function __invoke(ApiNotification $mailNotification)
    {
      $this->service->uploadProductsAPI();
    }
}