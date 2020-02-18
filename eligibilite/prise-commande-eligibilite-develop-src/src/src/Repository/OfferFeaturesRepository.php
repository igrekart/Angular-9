<?php

namespace App\Repository;

use App\Entity\OfferFeatures;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method OfferFeatures|null find($id, $lockMode = null, $lockVersion = null)
 * @method OfferFeatures|null findOneBy(array $criteria, array $orderBy = null)
 * @method OfferFeatures[]    findAll()
 * @method OfferFeatures[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OfferFeaturesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OfferFeatures::class);
    }

    // /**
    //  * @return OfferFeatures[] Returns an array of OfferFeatures objects
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
    public function findOneBySomeField($value): ?OfferFeatures
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
