<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;
use PDO;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{

    private EntityManagerInterface $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Product::class);
        $this->entityManager = $entityManager;
    }

    function findAllPaginated(array $queryArray) {
        $pageNum = $queryArray['pageNum'];
        $results = $queryArray['results'];
        $sortColumn = $queryArray['sortColumn'];
        $sortDirection = $queryArray['sortDirection'];

        $query = $this->createQueryBuilder('p')->orderBy("p.$sortColumn", $sortDirection)->getQuery();
        $paginator = new Paginator($query);
        $paginator->getQuery()->setFirstResult(($pageNum - 1) * $results)->setMaxResults($results);
        return $paginator;
    }

    function getProductsByListId(User $user, array $productsList): array {
        $productIds = implode(', ', array_map(static function ($product) {
            return $product->getId();
        }, $productsList));
    
        $sql = "SELECT p.*, COALESCE(cl.price, pl.price) AS final_price
                FROM price_list pl
                JOIN product p ON p.id = pl.product_id
                LEFT JOIN contract_list cl ON pl.product_id = cl.product_id and cl.user_id = :userId
                WHERE p.id IN ($productIds);";
    
        $conn = $this->getEntityManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('userId', $user->getId(), PDO::PARAM_INT);
        $result = $stmt->execute();
    
        $result = $result->fetchAllAssociative();
    
        $productRepository = $this->getEntityManager()->getRepository(Product::class);
        $hydratedProducts = [];
    
        foreach ($result as $item) {
            $product = $productRepository->find($item['id']);
            $product->setFinalPrice($item['final_price']);
            $hydratedProducts[] = $product;
        }
    
        return $hydratedProducts;
    }

    function getProductsFromCategoryHierachy(array $queryArray) {
        $categoryName = $queryArray['categoryName'];
        $productName = $queryArray['productName'];
        $sortColumn = $queryArray['sortColumn'];
        $sortDirection = $queryArray['sortDirection'];
        $limit = $queryArray['pageSize'];
        $offset = ($queryArray['pageNum'] - 1) * $limit;

        $order = "ORDER BY p.$sortColumn $sortDirection";

        $sql = 
            "WITH RECURSIVE CategoryHierarchy AS (
                SELECT id
                FROM category
                WHERE name = '$categoryName'
                UNION ALL
                SELECT c.id
                FROM category c
                JOIN CategoryHierarchy ch ON c.parent_category_id = ch.id
            )
            SELECT p.*
            FROM product p
            JOIN product_category pc ON p.id = pc.product_id
            WHERE pc.category_id IN (SELECT id FROM CategoryHierarchy) 
            AND p.name LIKE '%$productName%' 
            $order
            LIMIT $limit OFFSET $offset;
        ";
        $conn = $this->entityManager->getConnection();
        $stmt = $conn->prepare($sql);
        $result = $stmt->execute();
    
        return $result->fetchAllAssociative();
    }
    
    function getContractPriceListProductsFromCategoryHierarchy(array $queryArray) {
        $userId = $queryArray['userId'];
        $categoryName = $queryArray['categoryName'];
        $productName = $queryArray['productName'];
        $sortColumn = $queryArray['sortColumn'];
        $sortDirection = $queryArray['sortDirection'];
        $priceStart = $queryArray['priceStart'];
        $priceEnd = $queryArray['priceEnd'];
        $limit = $queryArray['pageSize'];
        $offset = ($queryArray['pageNum'] - 1) * $limit;

        $order = $sortColumn === 'price'? "ORDER BY pl.$sortColumn $sortDirection" : "ORDER BY p.$sortColumn $sortDirection";

        $sql = 
            "WITH RECURSIVE CategoryHierarchy AS (
                SELECT id
                FROM category
                WHERE name = '$categoryName'
                UNION ALL
                SELECT c.id
                FROM category c
                JOIN CategoryHierarchy ch ON c.parent_category_id = ch.id
            )
            SELECT p.*, COALESCE(cl.price, pl.price) AS price
            FROM product p
            JOIN product_category pc ON p.id = pc.product_id
            JOIN price_list pl on p.id = pl.product_id
            LEFT JOIN contract_list cl ON pl.product_id = cl.product_id and cl.user_id = $userId
            WHERE pc.category_id IN (SELECT id FROM CategoryHierarchy) 
            AND p.name LIKE '%$productName%' 
            AND COALESCE(cl.price, pl.price) BETWEEN $priceStart AND $priceEnd
            $order
            LIMIT $limit OFFSET $offset;
        ";
        $conn = $this->entityManager->getConnection();
        $stmt = $conn->prepare($sql);
        $result = $stmt->execute();
    
        return $result->fetchAllAssociative();
    }

    function getPriceListProductsFromCategoryHierarchy(array $queryArray) {
        $categoryName = $queryArray['categoryName'];
        $productName = $queryArray['productName'];
        $sortColumn = $queryArray['sortColumn'];
        $sortDirection = $queryArray['sortDirection'];
        $priceStart = $queryArray['priceStart'];
        $priceEnd = $queryArray['priceEnd'];
        $limit = $queryArray['pageSize'];
        $offset = ($queryArray['pageNum'] - 1) * $limit;

        $order = $sortColumn === 'price' ? "ORDER BY pl.$sortColumn $sortDirection" : "ORDER BY p.$sortColumn $sortDirection";
        
        $sql = 
            "WITH RECURSIVE CategoryHierarchy AS (
                SELECT id
                FROM category
                WHERE name = '$categoryName'
                UNION ALL
                SELECT c.id
                FROM category c
                JOIN CategoryHierarchy ch ON c.parent_category_id = ch.id
            )
            SELECT p.*, pl.price
            FROM product p
            JOIN product_category pc ON p.id = pc.product_id
            JOIN price_list pl on p.id = pl.product_id
            WHERE pc.category_id IN (SELECT id FROM CategoryHierarchy)
            AND p.name LIKE '%$productName%' 
            AND pl.price BETWEEN $priceStart AND $priceEnd 
            $order
            LIMIT $limit OFFSET $offset;
        ";
        $conn = $this->entityManager->getConnection();
        $stmt = $conn->prepare($sql);
        $result = $stmt->execute();
    
        return $result->fetchAllAssociative();
    }

    function getContractListProductsFromCategoryHierarchy(array $queryArray) {
        $userId = $queryArray['userId'];
        $categoryName = $queryArray['categoryName'];
        $productName = $queryArray['productName'];
        $sortColumn = $queryArray['sortColumn'];
        $sortDirection = $queryArray['sortDirection'];
        $priceStart = $queryArray['priceStart'];
        $priceEnd = $queryArray['priceEnd'];
        $limit = $queryArray['pageSize'];
        $offset = ($queryArray['pageNum'] - 1) * $limit;

        $order = $sortColumn === 'price' ? "ORDER BY cl.$sortColumn $sortDirection" : "ORDER BY p.$sortColumn $sortDirection";
        
        $sql = 
            "WITH RECURSIVE CategoryHierarchy AS (
                SELECT id
                FROM category
                WHERE name = '$categoryName'
                UNION ALL
                SELECT c.id
                FROM category c
                JOIN CategoryHierarchy ch ON c.parent_category_id = ch.id
            )
            SELECT p.*, cl.price 
            FROM product p 
            JOIN product_category pc ON p.id = pc.product_id 
            LEFT JOIN contract_list cl on p.id = cl.product_id AND cl.user_id = $userId 
            WHERE pc.category_id IN (SELECT id FROM CategoryHierarchy) 
            AND p.name LIKE '%$productName%' 
            AND cl.price BETWEEN $priceStart AND $priceEnd 
            $order
            LIMIT $limit OFFSET $offset;
        ";
        $conn = $this->entityManager->getConnection();
        $stmt = $conn->prepare($sql);
        $result = $stmt->execute();
    
        return $result->fetchAllAssociative();
    }
    
}
