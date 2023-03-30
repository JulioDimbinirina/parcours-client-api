<?php

namespace App\Repository;

use App\Entity\RejectBdc;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RejectBdc|null find($id, $lockMode = null, $lockVersion = null)
 * @method RejectBdc|null findOneBy(array $criteria, array $orderBy = null)
 * @method RejectBdc[]    findAll()
 * @method RejectBdc[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RejectBdcRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RejectBdc::class);
    }

    // /**
    //  * @return RejectBdc[] Returns an array of RejectBdc objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RejectBdc
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
