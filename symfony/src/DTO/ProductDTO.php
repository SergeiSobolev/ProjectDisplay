<?php

namespace App\DTO;

use App\Entity\Product;

class ProductDTO
{
    private int $id;

    private string $name;

    private int $price;

    private string $description;

    private int $warehouse;

    private string $slug;

    private ?string $image;

    const PREFIX = 'product_cache_';

    public function mapFromEntity(Product $product): self
    {
        $this->id = $product->getId();
        $this->name = $product->getName();

        $this->description = $product->getDescription();
        $this->price = $product->getPrice();
        $this->warehouse = $product->getWarehouse();
        $this->slug = $product->getSlug();
        $this->image = $product->getImageProducts()->first()?$product->getImageProducts()->first()->getImage()->getFileName(): null;

        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getWarehouse(): int
    {
        return $this->warehouse;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }


    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setPrice(int $price): void
    {
        $this->price = $price;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function setWarehouse(int $warehouse): void
    {
        $this->warehouse = $warehouse;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): void
    {
        $this->image = $image;
    }
}