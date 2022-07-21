<?php

namespace App\Message;

use App\Service\MailerService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class MailNotificationHandler implements MessageHandlerInterface
{
    public function __construct(
        private readonly MailerService $mailer,
    ) {}

    public function __invoke(MailNotification $mailNotification)
    {
        if (MailNotification::USER_REGISTRATION == $mailNotification->getType()) {
            $this->mailer->registerProcessingEmail($mailNotification->getEmail(), $mailNotification->getconfirmationCode());
        }
    }
}