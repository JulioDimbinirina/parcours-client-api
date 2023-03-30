<?php

namespace App\Repository;

use App\Entity\LeadDetailOperation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LeadDetailOperation|null find($id, $lockMode = null, $lockVersion = null)
 * @method LeadDetailOperation|null findOneBy(array $criteria, array $orderBy = null)
 * @method LeadDetailOperation[]    findAll()
 * @method LeadDetailOperation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LeadDetailOperationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LeadDetailOperation::class);
    }

    public function deleteOperationViaHisId(int $id) {
        $req = 'START TRANSACTION; SET FOREIGN_KEY_CHECKS=0; DELETE FROM lead_detail_operation WHERE id = "'.$id.'"; SET FOREIGN_KEY_CHECKS=1; COMMIT;';
        $statement = $this->getEntityManager()->getConnection()->prepare($req);
        $statement->execute([]);
    }

    public function deleteRowInLeadDetailOperation(int $idResumeLead) {
        $req = 'START TRANSACTION; SET FOREIGN_KEY_CHECKS=0; DELETE FROM lead_detail_operation WHERE resume_lead_id = "'.$idResumeLead.'"; SET FOREIGN_KEY_CHECKS=1; COMMIT;';
        $statement = $this->getEntityManager()->getConnection()->prepare($req);
        $statement->execute([]);
    }

    // /**
    //  * @return LeadDetailOperation[] Returns an array of LeadDetailOperation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?LeadDetailOperation
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function deleteByResumeLeadId(int $id) {
        return $this->createQueryBuilder('d')
            ->delete()
            ->where('d.resumeLead = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }
}
