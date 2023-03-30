<?php

namespace App\Repository;

use App\Entity\BdcOperation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BdcOperation|null find($id, $lockMode = null, $lockVersion = null)
 * @method BdcOperation|null findOneBy(array $criteria, array $orderBy = null)
 * @method BdcOperation[]    findAll()
 * @method BdcOperation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BdcOperationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BdcOperation::class);
    }

    // /**
    //  * @return BdcOperation[] Returns an array of BdcOperation objects
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
    
    public  function getBdcOperationByBdcId($bdcId){
        $this->createQueryBuilder('b')
            ->join('b.operation','o')
            ->where('o.bdc = :bdcId');
    }
    public  function getBdcOperationByBdcId2($bdcId){
        $this->createQueryBuilder('b')
            ->where('b.bdc_id = :bdcId')
            ->setParameter('bdcId', $bdcId)
            ->getQuery()
            ->getResult()
            ;
    }
    public function getBdcOByidBdcO($idBdcO){
        return $this->createQueryBuilder('h')
        ->where('h.id = :idBdcO')
        ->setParameter('idBdcO', $idBdcO)
        ->getQuery()
        ->getResult();
    }
    public function findBdcOperationChild($id, $operationId, $bdcId)
    {
        return $this->createQueryBuilder('b')
            ->join('b.operation', 'o')
            ->join('b.bdc', 'd')
            ->where('o.id = :operation')
            ->andWhere('d.id = :bdc')
            ->andWhere('b.id != :bdcOperationId')
            ->setParameter('operation', $operationId)
            ->setParameter('bdc', $bdcId)
            ->setParameter('bdcOperationId', $id)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findParentBdcOperation($operationId, $bdcId)
    {
        return $this->createQueryBuilder('b')
            ->join('b.operation', 'o')
            ->join('b.bdc', 'd')
            ->where('o.id = :operation')
            ->andWhere('d.id = :bdc')
            ->andWhere('b.isHnoHorsDimanche is null')
            ->andWhere('b.isHnoDimanche is null')
            ->setParameter('operation', $operationId)
            ->setParameter('bdc', $bdcId)
            ->getQuery()
            ->getResult()
            ;
    }

    /*
    public function findOneBySomeField($value): ?BdcOperation
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

	/* public function deleteById(int $id) {
        return $this->createQueryBuilder('d')
            ->delete()
            ->where('d.bdc = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    } */

    public function deleteById(int $id) {
        $req = 'START TRANSACTION; SET FOREIGN_KEY_CHECKS=0; DELETE FROM bdc_operation WHERE id = "'.$id.'"; SET FOREIGN_KEY_CHECKS=1; COMMIT;';
        $statement = $this->getEntityManager()->getConnection()->prepare($req);
        $statement->execute([]);
    }
}
