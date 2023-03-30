<?php

namespace App\Repository;

use App\Entity\Contrat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Contrat|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contrat|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contrat[]    findAll()
 * @method Contrat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContratRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contrat::class);
    }

    public function getAllCustomerCreerOuModifier(){
        return $this->createQueryBuilder('c')
        ->where('c.statusContrat = :statusCreer or c.statusContrat = :statusModifier or c.statusContrat = :statusEnvoyer')
        ->setParameter('statusCreer', 1)
        ->setParameter('statusModifier', 2)
        ->setParameter('statusEnvoyer', 3)
        ->getQuery()
        ->getResult();
    }

    public function getAllContratSignatureNotNull(){
        return $this->createQueryBuilder('c')
        ->where('c.signaturePackContratCustomer IS NOT NULL')
        ->andWhere('c.statusContrat = :statusCreer or c.statusContrat = :statusModifier or c.statusContrat = :statusEnvoyer')
        ->setParameter('statusCreer', 1)
        ->setParameter('statusModifier', 2)
        ->setParameter('statusEnvoyer', 3)
        ->getQuery()
        ->getResult();
    }

    // /**
    //  * @return Contrat[] Returns an array of Contrat objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Contrat
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
