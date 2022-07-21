<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Product;
use App\Form\CartType;
use App\Manager\CartManager;
use App\Service\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CartController extends AbstractController
{
    public function __construct(private readonly MailerService $mailerService)
    {
    }

    public function showCart(CartManager $cartManager, Request $request, ValidatorInterface $validator): Response
    {
        $cart = $cartManager->getCurrentCart();
        $form = $this->createForm(CartType::class, $cart);
        $form->handleRequest($request);

        $errors = $validator->validate($cart);

        if ($form->isSubmitted() && $form->isValid()) {
            $cart->setCreatedAt(new \DateTime());
            $cartManager->save($cart);

            return $this->redirectToRoute('view_cart');
        }

        return $this->render('cart/cart.html.twig', [
            'cart' => $cart,
            'form' => $form->createView(),
            'errors' => $errors,
        ]);
    }

    public function checkout(CartManager $cartManager, ValidatorInterface $validator, EntityManagerInterface $em): Response
    {
        $cart = $cartManager->getCurrentCart();

        $errors = $validator->validate($cart);

        if ($errors->count()) {

            return $this->redirectToRoute('view_cart');
        }

        $cart->setStatus(Order::STATUS_PROCESSING);

        $cartManager->save($cart);

        $this->mailerService->orderProcessingEmail($cart);

        return $this->redirectToRoute('view_cart');
    }

    public function addProductCartAjax(
        EntityManagerInterface $em,
        CartManager            $cartManager,
        Product                $product,
        Request                $request,
    ): Response
    {
        $orderItems = $em->getRepository(OrderItem::class)->findBy(['product' => $product]);
        if ($request->request->get('method')) {
            if ($orderItems == null) {
                $orderItem = new OrderItem();
                $orderItem->setProduct($product);
                $orderItem->setQuantity(1);
                $orderItem->setOrderRef($cartManager->getCurrentCart());
            } else {
                $orderItem = $em->getRepository(OrderItem::class)->findOneBy(['product' => $product]);
                $quantityProduct = $orderItem->getQuantity();
                $orderItem->setQuantity($quantityProduct+1);
            }
        } else {
            $cart = $cartManager->getCurrentCart();
            foreach ($orderItems as $orderItem) {
                $cart->removeItem($orderItem);
            }
        }

        $em->persist($orderItem);
        $em->flush();
        return new Response();
    }
}
