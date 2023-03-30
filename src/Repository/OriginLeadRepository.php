<?php

namespace App\Repository;

use App\Entity\OriginLead;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OriginLead|null find($id, $lockMode = null, $lockVersion = null)
 * @method OriginLead|null findOneBy(array $criteria, array $orderBy = null)
 * @method OriginLead[]    findAll()
 * @method OriginLead[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OriginLeadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OriginLead::class);
    }

    // /**
    //  * @return OriginLead[] Returns an array of OriginLead objects
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
    public function findOneBySomeField($value): ?OriginLead
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
