<?php

namespace App\Repository;

use App\Entity\NaturePrestation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NaturePrestation|null find($id, $lockMode = null, $lockVersion = null)
 * @method NaturePrestation|null findOneBy(array $criteria, array $orderBy = null)
 * @method NaturePrestation[]    findAll()
 * @method NaturePrestation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NaturePrestationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NaturePrestation::class);
    }

    // /**
    //  * @return NaturePrestation[] Returns an array of NaturePrestation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?NaturePrestation
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
