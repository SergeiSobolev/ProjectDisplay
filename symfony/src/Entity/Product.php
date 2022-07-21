<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\Table(name: '`products`')]
class Product
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'integer')]
    private $price;

    #[ORM\Column(type: 'string', length: 255)]
    private $description;

    #[ORM\ManyToOne(targetEntity: Category::class, cascade: ['persist', 'remove'], inversedBy: 'products')]
    #[ORM\JoinColumn(nullable: false)]
    private $category;

    #[ORM\Column(type: 'datetime')]
    #[Gedmo\Timestampable(on: 'create')]
    private $createdAt;

    #[ORM\Column(type: 'integer')]
    private $warehouse;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ProductField::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private $productFields;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ImageProduct::class, cascade: ['persist', 'remove'])]
    private $imageProducts;

    #[ORM\Column(length: 16)]
    private $code;

    #[ORM\Column(length: 128, unique: true, nullable: true)]
    #[Gedmo\Slug(fields: ['name', 'code'])]
    private $slug;

    #[ORM\Column(type: 'integer', unique: true)]
    private $idApi;

    public function __construct()
    {
        $this->productFields = new ArrayCollection();
        $this->imageProducts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getWarehouse(): ?int
    {
        return $this->warehouse;
    }

    public function setWarehouse(int $warehouse): self
    {
        $this->warehouse = $warehouse;

        return $this;
    }

    public function getProductFields(): Collection
    {
        return $this->productFields;
    }

    public function addProductField(ProductField $productField): self
    {
        if (!$this->productFields->contains($productField)) {
            $this->productFields[] = $productField;
            $productField->setProduct($this);
        }

        return $this;
    }

    public function removeProductField(ProductField $productField): self
    {
        $this->productFields->removeElement($productField);
        return $this;
    }

    public function getProductPromo(): ?string
    {
        return $this->imageProducts->first() ? $this->imageProducts->first()->getImage()->getFilename() : null;
    }

    /**
     * @return Collection<int, ImageProduct>
     */
    public function getImageProducts(): Collection
    {
        return $this->imageProducts;
    }

    public function addImageProduct(ImageProduct $imageProduct): self
    {
        if (!$this->imageProducts->contains($imageProduct)) {
            $this->imageProducts[] = $imageProduct;
            $imageProduct->setProduct($this);
        }

        return $this;
    }

    public function removeImageProduct(ImageProduct $imageProduct): self
    {
        if ($this->imageProducts->removeElement($imageProduct)) {
            // set the owning side to null (unless already changed)
            if ($imageProduct->getProduct() === $this) {
                $imageProduct->setProduct(null);
            }
        }

        return $this;
    }

    public function getSlug(): mixed
    {
        return $this->slug;
    }

    public function getCode(): mixed
    {
        return $this->code;
    }

    public function setCode($code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getIdApi(): ?int
    {
        return $this->idApi;
    }

    public function setIdApi($idApi): self
    {
        $this->idApi = $idApi;

        return $this;
    }
}
