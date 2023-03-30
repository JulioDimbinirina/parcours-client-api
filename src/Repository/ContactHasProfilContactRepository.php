<?php

namespace App\Repository;

use App\Entity\ContactHasProfilContact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ContactHasProfilContact|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContactHasProfilContact|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContactHasProfilContact[]    findAll()
 * @method ContactHasProfilContact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactHasProfilContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContactHasProfilContact::class);
    }

    public function deleteByContactId(int $id)
    {
        return $this->createQueryBuilder('c')
            ->delete()
            ->where('c.contact = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return ContactHasProfilContact[] Returns an array of ContactHasProfilContact objects
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
    public function findOneBySomeField($value): ?ContactHasProfilContact
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
