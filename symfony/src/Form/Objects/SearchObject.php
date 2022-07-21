<?php

namespace App\Form\Objects;


class SearchObject
{
    protected ?string $name = null;

    protected ?int $minPrice = null;

    protected ?int $maxPrice = null;

    protected ?array $category = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getMinPrice(): ?int
    {
        return $this->minPrice;
    }

    public function setMinPrice($minPrice): void
    {
        $this->minPrice = $minPrice;
    }

    public function getMaxPrice(): ?int
    {
        return $this->maxPrice;
    }

    public function setMaxPrice($maxPrice): void
    {
        $this->maxPrice = $maxPrice;
    }

    public function getCategory(): ?array
    {
        return $this->category;
    }

    public function setCategory(?array $category): void
    {
        $this->category = $category;
    }
}