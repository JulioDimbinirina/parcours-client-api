<?php

namespace App\Repository;

use App\Entity\ObjectifQualitatif;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ObjectifQualitatif|null find($id, $lockMode = null, $lockVersion = null)
 * @method ObjectifQualitatif|null findOneBy(array $criteria, array $orderBy = null)
 * @method ObjectifQualitatif[]    findAll()
 * @method ObjectifQualitatif[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ObjectifQualitatifRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ObjectifQualitatif::class);
    }

    // /**
    //  * @return ObjectifQualitatif[] Returns an array of ObjectifQualitatif objects
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
    public function findOneBySomeField($value): ?ObjectifQualitatif
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
