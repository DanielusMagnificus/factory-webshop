<?php

namespace App\Repository;

use App\Entity\PurchasedProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PurchasedProduct>
 *
 * @method PurchasedProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method PurchasedProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method PurchasedProduct[]    findAll()
 * @method PurchasedProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PurchasedProductRepository extends ServiceEntityRepository
{

    private EntityManagerInterface $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, PurchasedProduct::class);
        $this->entityManager = $entityManager;
    }

    function save(PurchasedProduct $purchasedProduct) {
        $this->entityManager->persist($purchasedProduct);
        $this->entityManager->flush();
    }

//    /**
//     * @return PurchasedProduct[] Returns an array of PurchasedProduct objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PurchasedProduct
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
