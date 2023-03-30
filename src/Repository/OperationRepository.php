<?php

namespace App\Repository;

use App\Entity\Operation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Operation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Operation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Operation[]    findAll()
 * @method Operation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OperationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Operation::class);
    }

    public function GetAllOperation(){
        $qb = $this
        ->getEntityManager()
        ->createQuery("SELECT b FROM " . $this->getEntityName()." b")
        ;
		return $qb->getResult();
    }

    public function GetAllOperation2(){
        $rawSql = "SELECT * FROM operation";
        $stmt = $this->getEntityManager()->getConnection()->prepare($rawSql);
        $stmt->execute([]);
    
        return $stmt->fetchAll();
    }

    public function truncateTableOperation() {
        $req = 'START TRANSACTION; SET FOREIGN_KEY_CHECKS=0; TRUNCATE TABLE operation; SET FOREIGN_KEY_CHECKS=1; COMMIT;';
        $statement = $this->getEntityManager()->getConnection()->prepare($req);
        $statement->execute([]);
        // return $statement->fetchAll();
    }

    public function getOperationWithoutHno(int $idFamilleOperation)
    {
        return $this->createQueryBuilder('o')
            ->join('o.familleOperation', 'f')
            ->where('f.id = :idFamilleOperation')
            ->andWhere('o.libelle not like :txtHno')
            ->setParameter('idFamilleOperation', $idFamilleOperation)
            ->setParameter('txtHno', '%hno%')
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Operation[] Returns an array of Operation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Operation
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
