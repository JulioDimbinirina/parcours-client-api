<?php

namespace App\Repository;

use App\Entity\HauseIndiceLignefacturation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method HauseIndiceLignefacturation|null find($id, $lockMode = null, $lockVersion = null)
 * @method HauseIndiceLignefacturation|null findOneBy(array $criteria, array $orderBy = null)
 * @method HauseIndiceLignefacturation[]    findAll()
 * @method HauseIndiceLignefacturation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HauseIndiceLignefacturationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HauseIndiceLignefacturation::class);
    }

    // /**
    //  * @return HauseIndiceLignefacturation[] Returns an array of HauseIndiceLignefacturation objects
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
    public function findOneBySomeField($value): ?HauseIndiceLignefacturation
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function add(HauseIndiceLignefacturation $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function getHausseBdcO($idHausseClient){
        return $this->createQueryBuilder('h')
        ->andWhere('h.hausseIndeceClient_id = :val')
        ->setParameter('val', $idHausseClient)
        ->getQuery()
        ->getResult()
    ;
    }

    public function setCommentHausseBdcO(int $idBdcOp,$commentaire) {
        $req = "UPDATE `hause_indice_lignefacturation` SET `commentaire_mod_manuel` = '".$commentaire."' WHERE `hause_indice_lignefacturation`.`id_operation` = ".$idBdcOp;
       /* $qb = $this
        ->getEntityManager()
        ->createQuery($req);*/ 

        $statement = $this->getEntityManager()->getConnection()->prepare($req);
        $statement->execute([]);
		return "okok";
    }

    /*public function getBdcOByidBdcO($idBdcO){
        return $this->createQueryBuilder('h')
        ->where('h.id = : idBdcO')
        ->setParameter('idBdcO', $idBdcO)
        ->getQuery()
        ->getResult();
    }*/

}
