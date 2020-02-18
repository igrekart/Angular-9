<?php

namespace App\Repository;

use App\Entity\MobileMoney;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MobileMoney|null find($id, $lockMode = null, $lockVersion = null)
 * @method MobileMoney|null findOneBy(array $criteria, array $orderBy = null)
 * @method MobileMoney[]    findAll()
 * @method MobileMoney[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MobileMoneyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MobileMoney::class);
    }

    // /**
    //  * @return MobileMoney[] Returns an array of MobileMoney objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MobileMoney
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
