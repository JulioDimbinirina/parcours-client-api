<?php

namespace App\Repository;

use App\Entity\Bu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Bu|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bu|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bu[]    findAll()
 * @method Bu[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bu::class);
    }

    // /**
    //  * @return Bu[] Returns an array of Bu objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Bu
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
