<?php

namespace App\Repository;

use App\Entity\DureeTrt;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DureeTrt|null find($id, $lockMode = null, $lockVersion = null)
 * @method DureeTrt|null findOneBy(array $criteria, array $orderBy = null)
 * @method DureeTrt[]    findAll()
 * @method DureeTrt[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DureeTrtRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DureeTrt::class);
    }

    public function getLibelleOfDureTrt(){
        return $this->createQueryBuilder('d')
            ->select('d.libelle')
            ->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return DureeTrt[] Returns an array of DureeTrt objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DureeTrt
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
