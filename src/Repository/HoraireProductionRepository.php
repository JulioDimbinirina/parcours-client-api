<?php

namespace App\Repository;

use App\Entity\HoraireProduction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method HoraireProduction|null find($id, $lockMode = null, $lockVersion = null)
 * @method HoraireProduction|null findOneBy(array $criteria, array $orderBy = null)
 * @method HoraireProduction[]    findAll()
 * @method HoraireProduction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HoraireProductionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HoraireProduction::class);
    }

    // /**
    //  * @return HoraireProduction[] Returns an array of HoraireProduction objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?HoraireProduction
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
