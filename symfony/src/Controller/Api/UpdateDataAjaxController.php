<?php

namespace App\Controller\Api;

use App\Storage\CartSessionStorage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class UpdateDataAjaxController extends AbstractController
{
    public function __construct(
        private readonly CartSessionStorage $cartSessionStorage,
    ) {}

    public function getCountProductInCart(): JsonResponse
    {
        $countProductInCart = $this->cartSessionStorage->getCart()?$this->cartSessionStorage->getCart()->getItems()->count(): 0;

        return new JsonResponse($countProductInCart);
    }
}