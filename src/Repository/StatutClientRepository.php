<?php

namespace App\Repository;

use App\Entity\StatutClient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StatutClient|null find($id, $lockMode = null, $lockVersion = null)
 * @method StatutClient|null findOneBy(array $criteria, array $orderBy = null)
 * @method StatutClient[]    findAll()
 * @method StatutClient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatutClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StatutClient::class);
    }

    // /**
    //  * @return StatutClient[] Returns an array of StatutClient objects
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
    public function findOneBySomeField($value): ?StatutClient
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
