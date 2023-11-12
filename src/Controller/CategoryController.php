<?php

namespace App\Controller;

use App\Service\CategoryService;
use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{

    private CategoryService $categoryService;
    private ProductService $productService;

    function __construct(CategoryService $categoryService, ProductService $productService) {
        $this->categoryService = $categoryService;
        $this->productService = $productService;
    }

    /**
     * lists all categories
     */
    #[Route('/category/list-categories', name: 'category_list_categories')]
    public function getCategoryListCategories(): JsonResponse
    {

        $allCategories = $this->categoryService->findAll();

        $formattedCategories = [];
        foreach ($allCategories as $category) {
            $formattedCategories[] = [
                "id" => $category->getId(),
                "name" => $category->getName(),
                "description" => $category->getDescription()
            ];
        }

        return $this->json($formattedCategories);
    }

    /**
     * lists all products inside a category hierarchy
     */
    #[Route('/category/list-products', name: 'category_list_products')]
    public function getCategoryListProducts(Request $request): JsonResponse
    {

        $queryArray = [
            'categoryName' => $request->query->get("category", "ALL"),
            'productName' => $request->query->get("productName", ""),
            'pageNum' => $request->query->get("page", 1),
            'pageSize' => $request->query->get("results", 100),
            'sortColumn' => $request->query->get("sortColumn", "id"),
            'sortDirection' => $request->query->get("sortDirection", "ASC")
        ];

        $productsFromCategoryName = $this->productService->getProductsFromCategoryHierachy($queryArray);

        return $this->json($productsFromCategoryName);
    }
}
