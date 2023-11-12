<?php

namespace App\Service;

use App\Entity\Product;
use App\Entity\User;
use App\Repository\ProductRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class ProductService {

    private ProductRepository $productRepository;

    function __construct(ProductRepository $productRepository) {
        $this->productRepository = $productRepository;
    }

    function findAllPaginated(array $queryArray) : Paginator {
        return $this->productRepository->findAllPaginated($queryArray);
    }

    function findOneById(int $id) {
        return $this->productRepository->findOneById($id);
    }

    function getProductsByListId(User $user, array $productsList) : array {
        return $this->productRepository->getProductsByListId($user, $productsList);
    }

    function getContractPriceListProductsFromCategoryHierarchy(array $queryArray) : array {
        $userId = $queryArray['userId'];

        if ($userId === null) {
            return $this->productRepository->getPriceListProductsFromCategoryHierarchy($queryArray);
        }
        return $this->productRepository->getContractPriceListProductsFromCategoryHierarchy($queryArray);
    }

    function getContractListProductsFromCategoryHierarchy(array $queryArray) : array {
        $userId = $queryArray['userId'];

        if ($userId !== null) {
            return $this->productRepository->getContractListProductsFromCategoryHierarchy($queryArray);
        }

        throw new Exception("UserId missing.");
    }

    function getPriceListProductsFromCategoryHierarchy(array $queryArray) : array {
        return $this->productRepository->getPriceListProductsFromCategoryHierarchy($queryArray);
    }

    function getProductsFromCategoryHierachy(array $queryArray) : array {
        return $this->productRepository->getProductsFromCategoryHierachy($queryArray);
    }
}