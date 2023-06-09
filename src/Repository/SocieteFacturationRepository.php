<?php

namespace App\Repository;

use App\Entity\SocieteFacturation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SocieteFacturation|null find($id, $lockMode = null, $lockVersion = null)
 * @method SocieteFacturation|null findOneBy(array $criteria, array $orderBy = null)
 * @method SocieteFacturation[]    findAll()
 * @method SocieteFacturation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SocieteFacturationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SocieteFacturation::class);
    }

    // /**
    //  * @return SocieteFacturation[] Returns an array of SocieteFacturation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SocieteFacturation
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
