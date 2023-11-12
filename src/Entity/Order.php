<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`orders`')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $orderDateTime = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'order_', targetEntity: PurchasedProduct::class, orphanRemoval: true)]
    private Collection $purchasedProducts;

    #[ORM\ManyToMany(targetEntity: Discount::class, mappedBy: 'orders', cascade: ['persist'])]
    private Collection $discounts;

    public function __construct()
    {
        $this->purchasedProducts = new ArrayCollection();
        $this->discounts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getOrderDateTime(): ?\DateTimeInterface
    {
        return $this->orderDateTime;
    }

    public function setOrderDateTime(\DateTimeInterface $orderDateTime): static
    {
        $this->orderDateTime = $orderDateTime;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, PurchasedProduct>
     */
    public function getPurchasedProducts(): Collection
    {
        return $this->purchasedProducts;
    }

    public function addPurchasedProduct(PurchasedProduct $purchasedProduct): static
    {
        if (!$this->purchasedProducts->contains($purchasedProduct)) {
            $this->purchasedProducts->add($purchasedProduct);
            $purchasedProduct->setOrder($this);
        }

        return $this;
    }

    public function removePurchasedProduct(PurchasedProduct $purchasedProduct): static
    {
        if ($this->purchasedProducts->removeElement($purchasedProduct)) {
            // set the owning side to null (unless already changed)
            if ($purchasedProduct->getOrder() === $this) {
                $purchasedProduct->setOrder(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Discount>
     */
    public function getDiscounts(): Collection
    {
        return $this->discounts;
    }

    public function addDiscount(Discount $discount): static
    {
        if (!$this->discounts->contains($discount)) {
            $this->discounts->add($discount);
            $discount->addOrder($this);
        }

        return $this;
    }

    public function removeDiscount(Discount $discount): static
    {
        if ($this->discounts->removeElement($discount)) {
            $discount->removeOrder($this);
        }

        return $this;
    }
}
