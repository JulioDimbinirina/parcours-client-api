<?php

namespace App\Repository;

use App\Entity\IndicatorQualitatif;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method IndicatorQualitatif|null find($id, $lockMode = null, $lockVersion = null)
 * @method IndicatorQualitatif|null findOneBy(array $criteria, array $orderBy = null)
 * @method IndicatorQualitatif[]    findAll()
 * @method IndicatorQualitatif[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IndicatorQualitatifRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IndicatorQualitatif::class);
    }

    public function deleteByIdBdcOperation(int $id) {
        $req = 'START TRANSACTION; SET FOREIGN_KEY_CHECKS=0; DELETE FROM indicator_qualitatif WHERE bdc_operation_id = "'.$id.'"; SET FOREIGN_KEY_CHECKS=1; COMMIT;';
        $statement = $this->getEntityManager()->getConnection()->prepare($req);
        $statement->execute([]);
    }

    // /**
    //  * @return IndicatorQualitatif[] Returns an array of IndicatorQualitatif objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?IndicatorQualitatif
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
