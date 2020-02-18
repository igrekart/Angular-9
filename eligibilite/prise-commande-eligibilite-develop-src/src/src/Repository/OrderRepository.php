<?php

namespace App\Repository;

use App\Entity\OOrder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method OOrder|null find($id, $lockMode = null, $lockVersion = null)
 * @method OOrder|null findOneBy(array $criteria, array $orderBy = null)
 * @method OOrder[]    findAll()
 * @method OOrder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OOrder::class);
    }

    /**
     * @param int $value
     * @return OOrder[] Returns an array of OOrder objects
     */
    public function findUncompleted()
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.Step < 8')
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return OOrder[] Returns an array of OOrder objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OOrder
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
