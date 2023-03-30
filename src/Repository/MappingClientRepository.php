<?php

namespace App\Repository;

use App\Entity\MappingClient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MappingClient|null find($id, $lockMode = null, $lockVersion = null)
 * @method MappingClient|null findOneBy(array $criteria, array $orderBy = null)
 * @method MappingClient[]    findAll()
 * @method MappingClient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MappingClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MappingClient::class);
    }

    // /**
    //  * @return MappingClient[] Returns an array of MappingClient objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MappingClient
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function getLibelleMapping(){
        return $this->createQueryBuilder('m')
        ->select('m.libelle')
        ->getQuery()
        ->getResult();
    }
}
