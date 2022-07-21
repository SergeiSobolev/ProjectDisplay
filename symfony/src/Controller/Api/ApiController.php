<?php

namespace App\Controller\Api;

use App\Message\ApiNotification;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;

class ApiController extends AbstractController
{
    public function __construct(
        private readonly MessageBusInterface $bus,
    ) {}

    public function getProductAPI(): Response
    {
        $this->bus->dispatch(new ApiNotification());
        return $this->redirectToRoute('index');
    }

}