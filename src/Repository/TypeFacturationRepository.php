<?php

namespace App\Repository;

use App\Entity\TypeFacturation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TypeFacturation|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeFacturation|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeFacturation[]    findAll()
 * @method TypeFacturation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeFacturationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeFacturation::class);
    }

    // /**
    //  * @return TypeFacturation[] Returns an array of TypeFacturation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TypeFacturation
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
