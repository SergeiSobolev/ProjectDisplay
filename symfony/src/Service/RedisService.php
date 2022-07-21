<?php

namespace App\Service;

use App\DTO\ProductDTO;
use App\Repository\ProductRepository;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Component\Serializer\SerializerInterface;

class RedisService
{
    private $client;

    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly ProductRepository $productRepository,
    )
    {
        $this->client = RedisAdapter::createConnection('redis://@redis:6379');
    }

    public function populateProductFromDatabase(string $slug): ProductDTO
    {
        $product = $this->productRepository->findOneBy(['slug' => $slug]);
        $productDTO = new ProductDTO();
        return $productDTO->mapFromEntity($product);
    }

    public function setProductInRedis(ProductDTO $productDTO): ProductDTO
    {
        $json = $this->serializer->serialize($productDTO, 'json');

        $this->client->set(ProductDTO::PREFIX . $productDTO->getSlug(), $json, 'EX', 60);

        return $productDTO;
    }

    public function getProductFromRedis(string $slug): ProductDTO
    {
        $json = $this->client->get('product_cache_' . $slug);

        if (!$json) {
            $productDTO = $this->populateProductFromDatabase($slug);
            return $this->setProductInRedis($productDTO);
        }

        return $this->serializer->deserialize($json, ProductDTO::class, 'json');
    }
}
