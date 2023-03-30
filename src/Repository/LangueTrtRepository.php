<?php

namespace App\Repository;

use App\Entity\LangueTrt;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LangueTrt|null find($id, $lockMode = null, $lockVersion = null)
 * @method LangueTrt|null findOneBy(array $criteria, array $orderBy = null)
 * @method LangueTrt[]    findAll()
 * @method LangueTrt[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LangueTrtRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LangueTrt::class);
    }

    // /**
    //  * @return LangueTrt[] Returns an array of LangueTrt objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LangueTrt
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
