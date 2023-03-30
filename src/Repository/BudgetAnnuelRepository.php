<?php

namespace App\Repository;

use App\Entity\BudgetAnnuel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BudgetAnnuel|null find($id, $lockMode = null, $lockVersion = null)
 * @method BudgetAnnuel|null findOneBy(array $criteria, array $orderBy = null)
 * @method BudgetAnnuel[]    findAll()
 * @method BudgetAnnuel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BudgetAnnuelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BudgetAnnuel::class);
    }

    public function findByAnnee($annee, $pays1, $pays2, $pays3, $pays4) {
        return $this->createQueryBuilder('b')
            ->where('b.pays = :pays OR b.pays = :pays2 OR b.pays = :pays3 OR b.pays = :pays4')
            ->andWhere('b.annee = :annee')
            ->setParameters([
                'annee' => $annee,
                'pays' => $pays1,
                'pays2' => $pays2,
                'pays3' => $pays3,
                'pays4' => $pays4
            ])
            ->getQuery()
            ->getResult()
            ;
    }

    public function findByMonthJanuary($annee, $pays1, $pays2, $pays3, $pays4) {
        return $this->createQueryBuilder('b')
            ->select('b.caJanvier, b.pays')
            ->where('b.pays = :pays OR b.pays = :pays2 OR b.pays = :pays3 OR b.pays = :pays4')
            ->andWhere('b.annee = :annee')
            ->setParameters([
                'annee' => $annee,
                'pays' => $pays1,
                'pays2' => $pays2,
                'pays3' => $pays3,
                'pays4' => $pays4
            ])
            ->getQuery()
            ->getResult()
            ;
    }

    public function findByMonthFebruary($annee, $pays1, $pays2, $pays3, $pays4) {
        return $this->createQueryBuilder('b')
            ->select('b.caFevrier, b.pays')
            ->where('b.pays = :pays OR b.pays = :pays2 OR b.pays = :pays3 OR b.pays = :pays4')
            ->andWhere('b.annee = :annee')
            ->setParameters([
                'annee' => $annee,
                'pays' => $pays1,
                'pays2' => $pays2,
                'pays3' => $pays3,
                'pays4' => $pays4
            ])
            ->getQuery()
            ->getResult()
            ;
    }

    public function findByMonthMarch($annee, $pays1, $pays2, $pays3, $pays4) {
        return $this->createQueryBuilder('b')
            ->select('b.caMars, b.pays')
            ->where('b.pays = :pays OR b.pays = :pays2 OR b.pays = :pays3 OR b.pays = :pays4')
            ->andWhere('b.annee = :annee')
            ->setParameters([
                'annee' => $annee,
                'pays' => $pays1,
                'pays2' => $pays2,
                'pays3' => $pays3,
                'pays4' => $pays4
            ])
            ->getQuery()
            ->getResult()
            ;
    }

    public function findByMonthApril($annee, $pays1, $pays2, $pays3, $pays4) {
        return $this->createQueryBuilder('b')
            ->select('b.caAvril, b.pays')
            ->where('b.pays = :pays OR b.pays = :pays2 OR b.pays = :pays3 OR b.pays = :pays4')
            ->andWhere('b.annee = :annee')
            ->setParameters([
                'annee' => $annee,
                'pays' => $pays1,
                'pays2' => $pays2,
                'pays3' => $pays3,
                'pays4' => $pays4
            ])
            ->getQuery()
            ->getResult()
            ;
    }

    public function findByMonthMay($annee, $pays1, $pays2, $pays3, $pays4) {
        return $this->createQueryBuilder('b')
            ->select('b.caMai, b.pays')
            ->where('b.pays = :pays OR b.pays = :pays2 OR b.pays = :pays3 OR b.pays = :pays4')
            ->andWhere('b.annee = :annee')
            ->setParameters([
                'annee' => $annee,
                'pays' => $pays1,
                'pays2' => $pays2,
                'pays3' => $pays3,
                'pays4' => $pays4
            ])
            ->getQuery()
            ->getResult()
            ;
    }

    public function findByMonthJune($annee, $pays1, $pays2, $pays3, $pays4) {
        return $this->createQueryBuilder('b')
            ->select('b.caJuin, b.pays')
            ->where('b.pays = :pays OR b.pays = :pays2 OR b.pays = :pays3 OR b.pays = :pays4')
            ->andWhere('b.annee = :annee')
            ->setParameters([
                'annee' => $annee,
                'pays' => $pays1,
                'pays2' => $pays2,
                'pays3' => $pays3,
                'pays4' => $pays4
            ])
            ->getQuery()
            ->getResult()
            ;
    }

    public function findByMonthJuly($annee, $pays1, $pays2, $pays3, $pays4) {
        return $this->createQueryBuilder('b')
            ->select('b.caJuillet, b.pays')
            ->where('b.pays = :pays OR b.pays = :pays2 OR b.pays = :pays3 OR b.pays = :pays4')
            ->andWhere('b.annee = :annee')
            ->setParameters([
                'annee' => $annee,
                'pays' => $pays1,
                'pays2' => $pays2,
                'pays3' => $pays3,
                'pays4' => $pays4
            ])
            ->getQuery()
            ->getResult()
            ;
    }

    public function findByMonthAugust($annee, $pays1, $pays2, $pays3, $pays4) {
        return $this->createQueryBuilder('b')
            ->select('b.caAout, b.pays')
            ->where('b.pays = :pays OR b.pays = :pays2 OR b.pays = :pays3 OR b.pays = :pays4')
            ->andWhere('b.annee = :annee')
            ->setParameters([
                'annee' => $annee,
                'pays' => $pays1,
                'pays2' => $pays2,
                'pays3' => $pays3,
                'pays4' => $pays4
            ])
            ->getQuery()
            ->getResult()
            ;
    }

    public function findByMonthSeptember($annee, $pays1, $pays2, $pays3, $pays4) {
        return $this->createQueryBuilder('b')
            ->select('b.caSeptembre, b.pays')
            ->where('b.pays = :pays OR b.pays = :pays2 OR b.pays = :pays3 OR b.pays = :pays4')
            ->andWhere('b.annee = :annee')
            ->setParameters([
                'annee' => $annee,
                'pays' => $pays1,
                'pays2' => $pays2,
                'pays3' => $pays3,
                'pays4' => $pays4
            ])
            ->getQuery()
            ->getResult()
            ;
    }

    public function findByMonthOctober($annee, $pays1, $pays2, $pays3, $pays4) {
        return $this->createQueryBuilder('b')
            ->select('b.caOctobre, b.pays')
            ->where('b.pays = :pays OR b.pays = :pays2 OR b.pays = :pays3 OR b.pays = :pays4')
            ->andWhere('b.annee = :annee')
            ->setParameters([
                'annee' => $annee,
                'pays' => $pays1,
                'pays2' => $pays2,
                'pays3' => $pays3,
                'pays4' => $pays4
            ])
            ->getQuery()
            ->getResult()
            ;
    }

    public function findByMonthNovember($annee, $pays1, $pays2, $pays3, $pays4) {
        return $this->createQueryBuilder('b')
            ->select('b.caNovembre, b.pays')
            ->where('b.pays = :pays OR b.pays = :pays2 OR b.pays = :pays3 OR b.pays = :pays4')
            ->andWhere('b.annee = :annee')
            ->setParameters([
                'annee' => $annee,
                'pays' => $pays1,
                'pays2' => $pays2,
                'pays3' => $pays3,
                'pays4' => $pays4
            ])
            ->getQuery()
            ->getResult()
            ;
    }

    public function findByMonthDecember($annee, $pays1, $pays2, $pays3, $pays4) {
        return $this->createQueryBuilder('b')
            ->select('b.caDecembre, b.pays')
            ->where('b.pays = :pays OR b.pays = :pays2 OR b.pays = :pays3 OR b.pays = :pays4')
            ->andWhere('b.annee = :annee')
            ->setParameters([
                'annee' => $annee,
                'pays' => $pays1,
                'pays2' => $pays2,
                'pays3' => $pays3,
                'pays4' => $pays4
            ])
            ->getQuery()
            ->getResult()
            ;
    }
}
