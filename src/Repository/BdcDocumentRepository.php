<?php

namespace App\Repository;

use App\Entity\BdcDocument;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BdcDocument|null find($id, $lockMode = null, $lockVersion = null)
 * @method BdcDocument|null findOneBy(array $criteria, array $orderBy = null)
 * @method BdcDocument[]    findAll()
 * @method BdcDocument[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BdcDocumentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BdcDocument::class);
    }

    public function getBdcDocumentForThisCustomer ($idCustomer) {
        return $this->createQueryBuilder('d')
            ->join('d.bdc', 'b')
            ->join('b.resumeLead', 'r')
            ->join('r.customer', 'c')
            ->where('c.id = :customer')
            ->setParameter('customer', $idCustomer)
            ->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return BdcDocument[] Returns an array of BdcDocument objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BdcDocument
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
