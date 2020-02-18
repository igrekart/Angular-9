<?php

namespace App\Repository;

use App\Entity\PaymentChoice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method PaymentChoice|null find($id, $lockMode = null, $lockVersion = null)
 * @method PaymentChoice|null findOneBy(array $criteria, array $orderBy = null)
 * @method PaymentChoice[]    findAll()
 * @method PaymentChoice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaymentChoiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PaymentChoice::class);
    }

    // /**
    //  * @return PaymentChoice[] Returns an array of PaymentChoice objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PaymentChoice
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
