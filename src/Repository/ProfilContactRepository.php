<?php

namespace App\Repository;

use App\Entity\ProfilContact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProfilContact|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProfilContact|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProfilContact[]    findAll()
 * @method ProfilContact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProfilContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProfilContact::class);
    }

    // /**
    //  * @return ProfilContact[] Returns an array of ProfilContact objects
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
    public function findOneBySomeField($value): ?ProfilContact
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
