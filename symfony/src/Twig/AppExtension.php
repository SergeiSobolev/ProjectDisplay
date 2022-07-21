<?php

namespace App\Twig;

use App\Entity\Category;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Storage\CartSessionStorage;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function __construct(
        private readonly CartSessionStorage $cartSessionStorage,
        private readonly ProductRepository $productRepository,
    ) {}

    public function getFilters(): array
    {
        return [
            new TwigFilter('price', [$this, 'formatPrice']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('getAllCategories', [$this, 'getAllCategories']),
            new TwigFunction('isProductInCart', [$this, 'isProductInCart']),
            new TwigFunction('CountProductsInCart', [$this, 'countProductsInCart']),
            new TwigFunction('CountProductsInCategory', [$this, 'countProductsInCategory']),
        ];
    }

    public function isProductInCart(Product $product): bool
    {
        $result = [];

        $orderItems = $this->cartSessionStorage->getCart()?->getItems();
        if ($orderItems === null) {
            return false;
        }

        foreach ($orderItems as $orderItem) {
            $result[] = $orderItem->getPRoduct()->getId();
        }
        return in_array($product->getId(), $result);
    }

    public function countProductsInCart(): ?int
    {
        return $this->cartSessionStorage->getCart()?->getItems()->count();
    }

    public function formatPrice($number): string
    {
        return $number . ' rub';
    }

    public function countProductsInCategory($categoryId): ?int
    {
        return $this->productRepository->getCountProductFromCategory($categoryId);
    }

}