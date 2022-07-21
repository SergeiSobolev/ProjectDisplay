<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\AddToCartType;
use App\Form\ProductType;
use App\Manager\CartManager;
use App\Service\RedisService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends AbstractController
{
    public function __construct(
        private readonly RedisService $redisService
    )
    {}

    public function showProduct(Product $product, Request $request, CartManager $cartManager): Response
    {
        $productDTO = $this->redisService->getProductFromRedis($product->getSlug());

        $form = $this->createForm(AddToCartType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $item = $form->getData();
            $item->setProduct($product);

            $cart = $cartManager->getCurrentCart();
            $cart
                ->addItem($item)
                ->setUpdatedAt(new \DateTime());

            $cartManager->save($cart);

            return $this->redirectToRoute('view_product', ['product' => $product->getId(),  'productDTO' => $productDTO]);
        }

        return $this->render('product/product.html.twig', [
            'product' => $product,
            'productDTO' => $productDTO,
            'form' => $form->createView()
        ]);
    }

    public function addProduct(Request $request, EntityManagerInterface $em): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('index');
        }

        return $this->render('product/product_manipulation.html.twig', [
            'form' => $form->createView(),
            'add' => 'method'
        ]);
    }

    public function editProduct(Product $product, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('index');
        }

        return $this->render('product/product_manipulation.html.twig', [
            'form' => $form->createView(),
            'edit' => 'method'
        ]);
    }

    public function deleteProduct(EntityManagerInterface $em, Product $product): Response
    {
        $em->remove($product);
        $em->flush();

        return $this->redirectToRoute('index');
    }
}