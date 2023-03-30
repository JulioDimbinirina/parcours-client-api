<?php

namespace App\Repository;

use App\Entity\FamilleOperation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FamilleOperation|null find($id, $lockMode = null, $lockVersion = null)
 * @method FamilleOperation|null findOneBy(array $criteria, array $orderBy = null)
 * @method FamilleOperation[]    findAll()
 * @method FamilleOperation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FamilleOperationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FamilleOperation::class);
    }

    public function truncateTableFamilleOperation() {
        $req = 'START TRANSACTION; SET FOREIGN_KEY_CHECKS=0; TRUNCATE TABLE famille_operation; SET FOREIGN_KEY_CHECKS=1; COMMIT;';
        $statement = $this->getEntityManager()->getConnection()->prepare($req);
        $statement->execute([]);

        return "Ok";
    }

    // /**
    //  * @return FamilleOperation[] Returns an array of FamilleOperation objects
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
    public function findOneBySomeField($value): ?FamilleOperation
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
