<?php

namespace App\Repository;

use App\Entity\IndicatorQuantitatif;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method IndicatorQuantitatif|null find($id, $lockMode = null, $lockVersion = null)
 * @method IndicatorQuantitatif|null findOneBy(array $criteria, array $orderBy = null)
 * @method IndicatorQuantitatif[]    findAll()
 * @method IndicatorQuantitatif[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IndicatorQuantitatifRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IndicatorQuantitatif::class);
    }

    public function deleteByIdBdcOperation(int $id) {
        $req = 'START TRANSACTION; SET FOREIGN_KEY_CHECKS=0; DELETE FROM indicator_quantitatif WHERE bdc_operation_id = "'.$id.'"; SET FOREIGN_KEY_CHECKS=1; COMMIT;';
        $statement = $this->getEntityManager()->getConnection()->prepare($req);
        $statement->execute([]);
    }

    // /**
    //  * @return IndicatorQuantitatif[] Returns an array of IndicatorQuantitatif objects
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
    public function findOneBySomeField($value): ?IndicatorQuantitatif
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
