<?php

namespace App\Repository;

use App\Entity\Justification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Justification|null find($id, $lockMode = null, $lockVersion = null)
 * @method Justification|null findOneBy(array $criteria, array $orderBy = null)
 * @method Justification[]    findAll()
 * @method Justification[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JustificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Justification::class);
    }

    // /**
    //  * @return Justification[] Returns an array of Justification objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('j.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Justification
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
