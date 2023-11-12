<?php

namespace App\Repository;

use App\Entity\DeliveryAddress;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DeliveryAddress>
 *
 * @method DeliveryAddress|null find($id, $lockMode = null, $lockVersion = null)
 * @method DeliveryAddress|null findOneBy(array $criteria, array $orderBy = null)
 * @method DeliveryAddress[]    findAll()
 * @method DeliveryAddress[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeliveryAddressRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $manager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, DeliveryAddress::class);
        $this->manager = $manager;
    }

    function save(DeliveryAddress $deliveryAddress) {
        $this->manager->persist($deliveryAddress);
        $this->manager->flush();
    }

    function findDeliveryAddressByUser(User $user) : array {
        return $this->createQueryBuilder('a')
            ->andWhere('a.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    function findDeliveryAddressByIdAndUser(int $id, User $user) : DeliveryAddress {
        return $this->createQueryBuilder('a')
            ->andWhere('a.user = :user')
            ->setParameter('user', $user)
            ->andWhere('a.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

//    /**
//     * @return DeliveryAddress[] Returns an array of DeliveryAddress objects
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

//    public function findOneBySomeField($value): ?DeliveryAddress
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
