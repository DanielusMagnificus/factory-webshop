<?php

namespace App\Repository;

use App\Entity\Discount;
use App\Entity\Order;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use PDO;

/**
 * @extends ServiceEntityRepository<Discount>
 *
 * @method Discount|null find($id, $lockMode = null, $lockVersion = null)
 * @method Discount|null findOneBy(array $criteria, array $orderBy = null)
 * @method Discount[]    findAll()
 * @method Discount[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DiscountRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Discount::class);
    }

    function findActiveDiscountsByCodesArray(array $codesList, DateTime $currentDateTime) {
        
        return $this->createQueryBuilder('a')
                ->andWhere(':currentDateTime between a.dateStart and a.dateEnd')
                ->setParameter('currentDateTime', $currentDateTime)
                ->andWhere('a.code in (:codesList)')
                ->setParameter('codesList', $codesList)
                ->getQuery()
                ->getResult();
    }

    function getActivatedDiscountsForOrder(Order $order) {

        return $this->createQueryBuilder('d')
                ->join('d.orders', 'od')
                ->andWhere('od.id = :order')
                ->setParameter('order', $order)
                ->getQuery()
                ->getResult();
    }

//    /**
//     * @return Discount[] Returns an array of Discount objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Discount
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
