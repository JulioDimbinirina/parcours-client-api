<?php

namespace App\Repository;

use App\Entity\FicheClient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FicheClient|null find($id, $lockMode = null, $lockVersion = null)
 * @method FicheClient|null findOneBy(array $criteria, array $orderBy = null)
 * @method FicheClient[]    findAll()
 * @method FicheClient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FicheClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FicheClient::class);
    }

    public function findByIdCustomer($value) {
        return $this->createQueryBuilder('f')
            ->andWhere('f.customer = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    // /**
    //  * @return FicheClient[] Returns an array of FicheClient objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FicheClient
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
