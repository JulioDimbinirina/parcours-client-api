<?php

namespace App\Repository;

use App\Entity\StatusLead;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StatusLead|null find($id, $lockMode = null, $lockVersion = null)
 * @method StatusLead|null findOneBy(array $criteria, array $orderBy = null)
 * @method StatusLead[]    findAll()
 * @method StatusLead[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatusLeadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StatusLead::class);
    }

    // /**
    //  * @return StatusLead[] Returns an array of StatusLead objects
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
    public function findOneBySomeField($value): ?StatusLead
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
