<?php

namespace App\Repository;

use App\Entity\PaysFacturation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PaysFacturation|null find($id, $lockMode = null, $lockVersion = null)
 * @method PaysFacturation|null findOneBy(array $criteria, array $orderBy = null)
 * @method PaysFacturation[]    findAll()
 * @method PaysFacturation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaysFacturationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PaysFacturation::class);
    }

    // /**
    //  * @return PaysFacturation[] Returns an array of PaysFacturation objects
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
    public function findOneBySomeField($value): ?PaysFacturation
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
