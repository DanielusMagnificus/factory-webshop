<?php

namespace App\Service;

use App\Entity\PurchasedProduct;
use App\Repository\PurchasedProductRepository;
use Doctrine\ORM\EntityManagerInterface;

class PurchasedProductService {

    private PurchasedProductRepository $purchasedProductRepository;

    function __construct(PurchasedProductRepository $purchasedProductRepository) {
        $this->purchasedProductRepository = $purchasedProductRepository;
    }

    function save(PurchasedProduct $purchasedProduct) : void {
        $this->purchasedProductRepository->save($purchasedProduct);
    }
}