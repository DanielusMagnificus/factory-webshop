<?php

namespace App\Entity;

use App\Repository\PurchasedProductRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PurchasedProductRepository::class)]
class PurchasedProduct
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'purchasedProducts', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Product $product = null;

    #[ORM\ManyToOne(inversedBy: 'purchasedProducts', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Order $order_ = null;

    #[ORM\Column]
    private ?float $basePrice = null;

    #[ORM\Column]
    private ?float $priceAfterTax = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): static
    {
        $this->product = $product;

        return $this;
    }

    public function getOrder(): ?Order
    {
        return $this->order_;
    }

    public function setOrder(?Order $order_): static
    {
        $this->order_ = $order_;

        return $this;
    }

    public function getBasePrice(): ?float
    {
        return $this->basePrice;
    }

    public function setBasePrice(float $basePrice): static
    {
        $this->basePrice = $basePrice;

        return $this;
    }

    public function getPriceAfterTax(): ?float
    {
        return $this->priceAfterTax;
    }

    public function setPriceAfterTax(float $priceAfterTax): static
    {
        $this->priceAfterTax = $priceAfterTax;

        return $this;
    }

}
