<?php

namespace App\Service;

use App\Entity\Category;
use App\Entity\Image;
use App\Entity\ImageProduct;
use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiService
{
    public function __construct(
        private readonly HttpClientInterface    $client,
        private readonly CategoryRepository     $categoryRepository,
        private readonly ProductRepository      $productRepository,
        private readonly EntityManagerInterface $em,
    ) {}

    public function uploadProductsAPI(): void
    {
        $response = $this->client->request(
            'GET',
            'https://fakestoreapi.com/products'
        );

        $statusCode = $response->getStatusCode();

        if ($statusCode == 200) {
            $contentJson = $response->getContent();
            $contentArray = json_decode($contentJson);

            foreach ($contentArray as $productObj) {

                $product = $this->getOrCreateProduct($productObj->id);
                $category = $this->getOrCreateCategory($productObj->category);

                $product
                    ->setName(mb_substr($productObj->title, 0, 100))
                    ->setCode('2022')
                    ->setPrice(ceil($productObj->price))
                    ->setDescription(mb_substr($productObj->description, 0, 100))
                    ->setWarehouse($productObj->rating->count)
                    ->setIdApi($productObj->id)
                    ->setCategory($category);

                if ($product->getId() === null) {
                    $image = (new Image())
                        ->setFileName($productObj->image);

                    $imageProduct = (new ImageProduct())
                        ->setImage($image);

                    $product->addImageProduct($imageProduct);
                } else {
                    $product->getImageProducts()->first()->getImage()->setFileName($productObj->image);
                }

                $this->em->persist($product);
            }

            $this->em->flush();
        }
    }

    private function getOrCreateProduct(int $productApiId): Product
    {
        $product = $this->productRepository->searchByApiId($productApiId);
        $product->setIdApi($productApiId);
        if (!$product) {
            $product = new Product();
            $product->setIdApi($productApiId);

            return $product;
        }
        return $product;
    }

    private function getOrCreateCategory(string $productObjCategory): Category
    {
        $category = $this->categoryRepository->findOneBy(['name' => $productObjCategory]);

        if (!$category) {
            $category = (new Category())
                ->setName($productObjCategory)
                ->setCode('2022');

            return $category;
        }
        return $category;
    }
}
