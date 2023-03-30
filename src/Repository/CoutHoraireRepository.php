<?php

namespace App\Repository;

use App\Entity\CoutHoraire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * @method CoutHoraire|null find($id, $lockMode = null, $lockVersion = null)
 * @method CoutHoraire|null findOneBy(array $criteria, array $orderBy = null)
 * @method CoutHoraire[]    findAll()
 * @method CoutHoraire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CoutHoraireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CoutHoraire::class);
    }

    public function findWithTwoDate($pays) {

        $req = 'SELECT * FROM cout_horaire WHERE curdate() BETWEEN date_debut AND date_fin AND pays = "'.$pays.'"';
        $statement = $this->getEntityManager()->getConnection()->prepare($req);
        $statement->execute([]);
        return $statement->fetchAll();
    }

    public function removeData ($param) {
        $query = 'START TRANSACTION; SET FOREIGN_KEY_CHECKS=0; DELETE FROM `cout_horaire` WHERE `date_debut` ="'.$param.'"; SET FOREIGN_KEY_CHECKS=1; COMMIT;';
        $statement = $this->getEntityManager()->getConnection()->prepare($query);
        $statement->executeQuery();
    }

    public function updateData ($coutHoraire, $coutFormation, $dateDebut,
                                $dateFin, $pays, $bu, $langueSpec) {
        $query = 'UPDATE `cout_horaire` SET `cout_horaire` = "'.$coutHoraire.'", `cout_formation` = "'.$coutFormation.'" WHERE `date_debut` = "'.$dateDebut.'" AND `date_fin` = "'.$dateFin.'" AND `pays` = "'.$pays.'" AND `bu` = "'.$bu.'" AND `langue_specialite` = "'.$langueSpec.'"';
        $statement = $this->getEntityManager()->getConnection()->prepare($query);
        $statement->executeQuery();
    }

    public function verifDataExist ($dateDebut, $dateFin, $pays, $bu, $langueSpec) {
        $query = 'SELECT * FROM `cout_horaire` WHERE `date_debut` = "'.$dateDebut.'" AND `date_fin` = "'.$dateFin.'" AND `pays` = "'.$pays.'" AND `bu` = "'.$bu.'" AND `langue_specialite` = "'.$langueSpec.'"';
        $statement = $this->getEntityManager()->getConnection()->prepare($query);
        $statement->executeQuery();
        return $statement->fetch();
    }

    public function verifDataExisteEntity  ($dateDebut, $dateFin, $pays, $bu, $langueSpec) {
        $qb = $this
        ->getEntityManager()
        ->createQuery("SELECT c FROM " . $this->getEntityName()." c WHERE c.dateDebut ='$dateDebut' AND c.dateFin = '$dateFin' AND c.pays = '$pays' AND c.bu = '$bu' AND c.langueSpecialite = '$langueSpec'")
        ;
    
        return $qb->getResult();
    }


    public function findAllDateCurrent() {

        $req = 'SELECT * FROM cout_horaire WHERE curdate() BETWEEN date_debut AND date_fin';
        $statement = $this->getEntityManager()->getConnection()->prepare($req);
        $statement->execute([]);
        return $statement->fetchAll();
    }

/*    public function getRefProfilAgent ($paysProd, $lngtrt, $bu) {
        return $this->createQueryBuilder('c')
            ->where('c.pays = :paysprod')
            ->andWhere('c.langueSpecialite = :lngtrt')
            ->andWhere('c.bu = :bu')
            ->setParameter('paysprod', $paysProd)
            ->setParameter('lngtrt', $lngtrt)
            ->setParameter('bu', $bu)
            ->getQuery()
            ->getResult()
            ;
    }*/

    public function getRefProfilAgent ($paysProd, $lngtrt, $bu) {
        $date = new \DateTime();

        return $this->createQueryBuilder('c')
            ->where('c.pays = :paysprod')
            ->andWhere('c.langueSpecialite = :lngtrt')
            ->andWhere('c.bu = :bu')
            ->andWhere(':date BETWEEN c.dateDebut AND c.dateFin')
            ->setParameter('paysprod', $paysProd)
            ->setParameter('lngtrt', $lngtrt)
            ->setParameter('bu', $bu)
            ->setParameter('date', $date)
            ->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return CoutHoraire[] Returns an array of CoutHoraire objects
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
    public function findOneBySomeField($value): ?CoutHoraire
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
