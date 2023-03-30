<?php

namespace App\Repository;

use App\Entity\WorkflowLead;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WorkflowLead|null find($id, $lockMode = null, $lockVersion = null)
 * @method WorkflowLead|null findOneBy(array $criteria, array $orderBy = null)
 * @method WorkflowLead[]    findAll()
 * @method WorkflowLead[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WorkflowLeadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WorkflowLead::class);
    }

    public function deleteRowInWorkFlowLead(int $id) {
        $req = 'START TRANSACTION; SET FOREIGN_KEY_CHECKS=0; DELETE FROM workflow_lead WHERE id = "'.$id.'"; SET FOREIGN_KEY_CHECKS=1; COMMIT;';
        $statement = $this->getEntityManager()->getConnection()->prepare($req);
        $statement->execute([]);
    }

    public function findlastrecentlyWorkflow(int $customer)
    {
        return $this->createQueryBuilder('w')
            // ->join('w.client', 'c')
            ->where('w.client = :client')
            ->setParameter('client', $customer)
            ->orderBy('w.date', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
            ;
    }
    // /**
    //  * @return WorkflowLead[] Returns an array of WorkflowLead objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?WorkflowLead
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
