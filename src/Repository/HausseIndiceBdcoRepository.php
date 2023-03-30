<?php

namespace App\Repository;

use App\Entity\HausseIndiceBdco;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method HausseIndiceBdco|null find($id, $lockMode = null, $lockVersion = null)
 * @method HausseIndiceBdco|null findOneBy(array $criteria, array $orderBy = null)
 * @method HausseIndiceBdco[]    findAll()
 * @method HausseIndiceBdco[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HausseIndiceBdcoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HausseIndiceBdco::class);
    }

    public function setComment(int $idBdcOp,$commentaire) {
        $req = "SELECT * FROM hausse_indice_syntec_client WHERE YEAR(date_years)>2021 and id_customer = 16";
        $qb = $this
        ->getEntityManager()
        ->createQuery("SELECT b FROM " . $this->getEntityName() . " b WHERE b.id_customer = $id AND b.dateYears >= '2023-01-01'");

		return $qb->getResult();
    }

    // /**
    //  * @return HausseIndiceBdco[] Returns an array of HausseIndiceBdco objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?HausseIndiceBdco
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
