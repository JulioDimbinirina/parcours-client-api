<?php

namespace App\Repository;

use App\Entity\ResumeLead;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ResumeLead|null find($id, $lockMode = null, $lockVersion = null)
 * @method ResumeLead|null findOneBy(array $criteria, array $orderBy = null)
 * @method ResumeLead[]    findAll()
 * @method ResumeLead[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResumeLeadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ResumeLead::class);
    }

    public function getMyResumeLeads(int $user) {
        return $this->createQueryBuilder('r')
            ->join('r.customer', 'c')
            ->join('c.user', 'u')
            ->where('u.id = :currentUser')
            ->setParameter('currentUser', $user)
            ->getQuery()
            ->getResult()
            ;
    }

    public function deleteRowInResumeLead(int $id) {
        $req = 'START TRANSACTION; SET FOREIGN_KEY_CHECKS=0; DELETE FROM resume_lead WHERE id = "'.$id.'"; SET FOREIGN_KEY_CHECKS=1; COMMIT;';
        $statement = $this->getEntityManager()->getConnection()->prepare($req);
        $statement->execute([]);
    }

    // /**
    //  * @return ResumeLead[] Returns an array of ResumeLead objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ResumeLead
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
