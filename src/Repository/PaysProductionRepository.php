<?php

namespace App\Repository;

use App\Entity\PaysProduction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PaysProduction|null find($id, $lockMode = null, $lockVersion = null)
 * @method PaysProduction|null findOneBy(array $criteria, array $orderBy = null)
 * @method PaysProduction[]    findAll()
 * @method PaysProduction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaysProductionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PaysProduction::class);
    }

    public function getLibelleOfPaysProd(){
        return $this->createQueryBuilder('p')
            ->select('p.libelle')
            ->getQuery()
            ->getResult()
            ;
    }

   /*  public function findByIdPaysProd(int $id)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult()
        ;
    }
 */
    // /**
    //  * @return PaysProduction[] Returns an array of PaysProduction objects
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
    public function findOneBySomeField($value): ?PaysProduction
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
