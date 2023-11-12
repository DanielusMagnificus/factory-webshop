<?php

namespace App\Service;

use App\Repository\CategoryRepository;
use Exception;

class CategoryService {

    private CategoryRepository $categoryRepository;

    function __construct(CategoryRepository $categoryRepository) {
        $this->categoryRepository = $categoryRepository;
    }
    
    function findAll() : array {
        return $this->categoryRepository->findAll();
    }

    function getProductsFromCategoryHierachy(array $queryArray) : array {
        return $this->categoryRepository->getProductsFromCategoryHierachy($queryArray);
    }
}