<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $SKU = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $published = null;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'products')]
    private Collection $categories;

    #[ORM\OneToMany(mappedBy: 'products', targetEntity: PurchasedProduct::class)]
    private Collection $purchasedProducts;

    #[ORM\ManyToMany(targetEntity: PriceList::class, mappedBy: 'products')]
    private Collection $priceLists;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: ContractList::class, orphanRemoval: true)]
    private Collection $contractLists;

    #[ORM\Transient]
    private ?float $finalPrice;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->purchasedProducts = new ArrayCollection();
        $this->priceLists = new ArrayCollection();
        $this->contractLists = new ArrayCollection();
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getSKU(): ?string
    {
        return $this->SKU;
    }

    public function setSKU(string $SKU): static
    {
        $this->SKU = $SKU;

        return $this;
    }

    public function getPublished(): ?\DateTimeInterface
    {
        return $this->published;
    }

    public function setPublished(\DateTimeInterface $published): static
    {
        $this->published = $published;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
        }

        return $this;
    }

    public function removeCategory(Category $category): static
    {
        $this->categories->removeElement($category);

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
            $purchasedProduct->setProducts($this);
        }

        return $this;
    }

    public function removePurchasedProduct(PurchasedProduct $purchasedProduct): static
    {
        if ($this->purchasedProducts->removeElement($purchasedProduct)) {
            // set the owning side to null (unless already changed)
            if ($purchasedProduct->getProducts() === $this) {
                $purchasedProduct->setProducts(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PriceList>
     */
    public function getPriceLists(): Collection
    {
        return $this->priceLists;
    }

    public function addPriceList(PriceList $priceList): static
    {
        if (!$this->priceLists->contains($priceList)) {
            $this->priceLists->add($priceList);
            $priceList->addProduct($this);
        }

        return $this;
    }

    public function removePriceList(PriceList $priceList): static
    {
        if ($this->priceLists->removeElement($priceList)) {
            $priceList->removeProduct($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, ContractList>
     */
    public function getContractLists(): Collection
    {
        return $this->contractLists;
    }

    public function addContractList(ContractList $contractList): static
    {
        if (!$this->contractLists->contains($contractList)) {
            $this->contractLists->add($contractList);
            $contractList->setProduct($this);
        }

        return $this;
    }

    public function removeContractList(ContractList $contractList): static
    {
        if ($this->contractLists->removeElement($contractList)) {
            // set the owning side to null (unless already changed)
            if ($contractList->getProduct() === $this) {
                $contractList->setProduct(null);
            }
        }

        return $this;
    }

    public function getFinalPrice(): ?float
    {
        return $this->finalPrice;
    }

    public function setFinalPrice(float $finalPrice): self
    {
        $this->finalPrice = $finalPrice;
        return $this;
    }

}
