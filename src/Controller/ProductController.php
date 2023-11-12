<?php

namespace App\Controller;

use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends AbstractController
{

    private ProductService $productService;

    function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * lists all products paginated
     * RequestParams: {page, results, sortColumn, sortDirection}
     */
    #[Route('/products', name: 'products')]
    public function productsList(Request $request): JsonResponse
    {

        $queryArray = [
            'pageNum' => $request->query->get('page', 1),
            'results' => $request->query->get('results', 10),
            'sortColumn' => $request->query->get('sortColumn', 'id'),
            'sortDirection' => $request->query->get('sortDirection', 'ASC')
        ];

        $productsList = $this->productService->findAllPaginated($queryArray);

        $formattedProducts = [];
        foreach ($productsList as $product) {
            $formattedProducts[] = [
                "id" => $product->getId(),
                "name" => $product->getName(),
                "description" => $product->getDescription(),
                "SKU" => $product->getSKU(),
                "published" => $product->getPublished()
            ];
        }
        return $this->json($formattedProducts);
    }

    /**
     * lists a single product by id
     */
    #[Route('/products/product/{productId}', name: 'product_details')]
    public function getProductDetails(int $productId): JsonResponse
    {        
        $product = $this->productService->findOneById($productId);

        return $this->json([
            "id" => $product->getId(),
            "name" => $product->getName(),
            "description" => $product->getDescription(),
            "SKU" => $product->getSKU(),
            "published" => $product->getPublished()
        ]);
    }

    /**
     * lists all contract list + price list products for a specific user
     * RequestParams: {category, productName, priceStart, priceEnd, page, results, sort, order}
     */
    #[Route('/products/user/{userId}/contract-price-list', name: 'user_contract_price_list')]
    public function getUserContractPriceList(Request $request, int $userId): JsonResponse
    {        
        $queryArray = [
            'userId' => $userId,
            'categoryName' => $request->query->get("category", "ALL"),
            'productName' => $request->query->get("productName", ""),
            'priceStart' => $request->query->get("priceStart", 0),
            'priceEnd' => $request->query->get("priceEnd", 999999),
            'pageNum' => $request->query->get("page", 1),
            'pageSize' => $request->query->get("results", 10),
            'sortColumn' => $request->query->get("sortColumn", "id"),
            'sortDirection' => $request->query->get("sortDirection", "ASC")
        ];

        $productsList = $this->productService->getContractPriceListProductsFromCategoryHierarchy($queryArray);

        return $this->json([
            'len' => count($productsList),
            'products' => $productsList,
        ]);
    }

    /**
     * lists products that belong on the contract list for a specific user
     * RequestParams: {category, productName, priceStart, priceEnd, page, results, sort, order}
     */
    #[Route('/products/user/{userId}/contract-list', name: 'user_contract_list')]
    public function getUserContractList(Request $request, int $userId): JsonResponse
    {        

        $queryArray = [
            'userId' => $userId,
            'categoryName' => $request->query->get("category", "ALL"),
            'productName' => $request->query->get("productName", ""),
            'priceStart' => $request->query->get("priceStart", 0),
            'priceEnd' => $request->query->get("priceEnd", 999999),
            'pageNum' => $request->query->get("page", 1),
            'pageSize' => $request->query->get("results", 10),
            'sortColumn' => $request->query->get("sortColumn", "id"),
            'sortDirection' => $request->query->get("sortDirection", "ASC")
        ];

        $productsList = $this->productService->getContractListProductsFromCategoryHierarchy($queryArray);

        return $this->json([
            'len' => count($productsList),
            'products' => $productsList,
        ]);
    }

    /**
     * lists all price list products for a specific user
     * RequestParams: {category, productName, priceStart, priceEnd, page, results, sort, order}
     */
    #[Route('/products/price-list', name: 'price_list')]
    public function getPriceList(Request $request): JsonResponse
    {        

        $queryArray = [
            'categoryName' => $request->query->get("category", "ALL"),
            'productName' => $request->query->get("productName", ""),
            'priceStart' => $request->query->get("priceStart", 0),
            'priceEnd' => $request->query->get("priceEnd", 999999),
            'pageNum' => $request->query->get("page", 1),
            'pageSize' => $request->query->get("results", 10),
            'sortColumn' => $request->query->get("sortColumn", "id"),
            'sortDirection' => $request->query->get("sortDirection", "ASC")
        ];

        $productsList = $this->productService->getPriceListProductsFromCategoryHierarchy($queryArray);

        return $this->json([
            'len' => count($productsList),
            'products' => $productsList,
        ]);
    }
}
