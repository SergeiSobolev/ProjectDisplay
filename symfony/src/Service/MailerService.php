<?php

namespace App\Service;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class MailerService
{
    public function __construct(
        private readonly MailerInterface        $mailer,
        private readonly EntityManagerInterface $em,
    ) {}

    public function sendEmail(string $to, string $subject, string $htmlTemplate, array $context): void
    {
        $email = (new TemplatedEmail())
            ->from('sergey.s@zimalab.com')
            ->to(new Address($to))
            ->subject($subject)

            // путь шаблона Twig для отображения
            ->htmlTemplate($htmlTemplate)

            // передайте переменные (name => value) to the template
            ->context($context);

        $this->mailer->send($email);
    }

    public function orderProcessingEmail(Order $cart): void
    {
        $orderItems = $this->em->getRepository(OrderItem::class)->findBy(['orderRef' => $cart->getId()]);

        $this->sendEmail(
            'sergey.s@zimalab.com',
            'Тема сообщения',
            'emails/firstsendmail.html.twig',
            [
                'expiration_date' => new \DateTime(),
                'username' => 'sergey',
                'orderItems' => $orderItems
            ]
        );
    }

    public function registerProcessingEmail(string $email,string $confirmationCode): void
    {
        $this->sendEmail(
            'sergey.s@zimalab.com',
            'Вы успешно зарегестрированны на zimalab.ru!',
            'emails/user_registered_mail.html.twig',
            [
                'expiration_date' => new \DateTime(),
                'emailUser' => $email,
                'confirmationCode' => $confirmationCode,
                'company_name' => 'Zimalab',

            ]
        );
    }
}