<?php

namespace App\Repository;

use App\Entity\HausseIndiceSyntecClient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method HausseIndiceSyntecClient|null find($id, $lockMode = null, $lockVersion = null)
 * @method HausseIndiceSyntecClient|null findOneBy(array $criteria, array $orderBy = null)
 * @method HausseIndiceSyntecClient[]    findAll()
 * @method HausseIndiceSyntecClient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HausseIndiceSyntecClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HausseIndiceSyntecClient::class);
    }

    // /**
    //  * @return HausseIndiceSyntecClient[] Returns an array of HausseIndiceSyntecClient objects
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
    public function findOneBySomeField($value): ?HausseIndiceSyntecClient
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function add(HausseIndiceSyntecClient $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function GetByCustomerYears($idCustomer){
        $dataFrom= $this->createQueryBuilder('h')
        ->select('MAX(h.dateYears) , MAX(h.id)')
        ->where('h.id_customer = :idCustomer')
        ->setParameter('idCustomer', $idCustomer)
        ->getQuery()
        ->getResult()
        ;
        return $this->createQueryBuilder('h')
        ->where('h.dateYears = :maxDate')
        ->andWhere('h.id = :id')
        ->setParameter('id', $dataFrom[0][2])
        ->setParameter('maxDate', $dataFrom[0][1])
        ->getQuery()
        ->getOneOrNullResult()
        ;
    }

    public function CheckCustomerAJourOrNote(HausseIndiceSyntecClient $client){
        //Mety
        //$yearCalcule=date("Y", strtotime('-1 years'));
        //test
        $yearCalcule=date("Y");
        $timeFirst = strtotime('01/01/'.$yearCalcule);
        $newformat = date('Y-m-d',$timeFirst);
        $timeLast = strtotime('12/31/'.$yearCalcule);
        $newformat1 = date('Y-m-d',$timeLast);
        return $this->createQueryBuilder('h') 
        ->Where('h.id_customer = :id_customer')
        ->andWhere('h.dateYears BETWEEN :timeFirst AND :timeLast')
        ->setParameter('id_customer', $client->getIdCustomer())
        ->setParameter('timeFirst',$newformat)
        ->setParameter('timeLast',$newformat1)
        ->getQuery()
        ->getResult()
        ;
    }
    public function CheckCustomerAJourOrNotByRsAndYears($idCustomer){
        //Mety
        //$yearCalcule=date("Y", strtotime('-1 years'));
        //test
        $yearCalcule=date("Y");
        $timeFirst = strtotime('01/01/'.$yearCalcule);
        $newformat = date('Y-m-d',$timeFirst);
        $timeLast = strtotime('12/31/'.$yearCalcule);
        $newformat1 = date('Y-m-d',$timeLast);
        return $this->createQueryBuilder('h')
        ->Where('h.id_customer = :id_customer')
        ->andWhere('h.dateYears BETWEEN :timeFirst AND :timeLast')
        ->setParameter('id_customer',$idCustomer)
        ->setParameter('timeFirst',$newformat)
        ->setParameter('timeLast',$newformat1)
        ->getQuery()
        ->getResult()
        ;
    }

    public function UpdateHausseIndiceSyntecClient(HausseIndiceSyntecClient $HausseClient){
        $idCustomer=$HausseClient->getIdCustomer();
        $dateYears=$HausseClient->getDateYears();
        $dateNow = (new \DateTime());

        $yearCalcule=date("Y");
        $timeFirst = strtotime('01/01/'.$yearCalcule);
        $newformat = date('Y-m-d',$timeFirst);
        $timeLast = strtotime('12/31/'.$yearCalcule);
        $newformat1 = date('Y-m-d',$timeLast);

        $HausseTalou= $this->createQueryBuilder('h')
        ->where('h.id_customer = :idCustomer')
        ->andWhere('h.dateYears BETWEEN :timeFirst AND :timeLast')
        ->setParameter('idCustomer', $idCustomer)
        ->setParameter('timeFirst',$newformat)
        ->setParameter('timeLast',$newformat1)
        ->getQuery()
        ->getResult()
        ;

        $HausseClientTaloa=$this->find($HausseTalou[0]->getId());
        $HausseClientTaloa->setActuel($HausseClient->getActuel());
        $HausseClientTaloa->setClause($HausseClient->getClause());
        $HausseClientTaloa->setDateContrat($HausseClient->getDateContrat());
        $HausseClientTaloa->setDateYears($HausseClient->getDateYears());
        $HausseClientTaloa->setIdCustomer($HausseClient->getIdCustomer());
        $HausseClientTaloa->setInitial($HausseClient->getInitial());
        $HausseClientTaloa->setIsType($HausseClient->getIsType());
        $HausseClientTaloa->setStatus($HausseClient->getStatus());
        $HausseClientTaloa->setTauxEvolution($HausseClient->getTauxEvolution());
        $HausseClientTaloa->setDateAplicatif($HausseClient->getDateAplicatif());
        $HausseClientTaloa->setCommentaire($HausseClient->getCommentaire());
       
        $this->_em->flush();
        
    }

    public function UpdateStatusHausse($idCustomer){
        $res=$this
        ->findOneBy(
            ['id_customer' => $idCustomer]
        );
        $res->setStatus(2);
        $this->_em->flush();
        return $res;
    }

    public function getByYearsCurrentByIdCustomer($idCustomer){
        $yearCalcule=date("Y");
        $timeFirst = strtotime('01/01/'.$yearCalcule);
        $newformat = date('Y-m-d',$timeFirst);
        $timeLast = strtotime('12/31/'.$yearCalcule);
        $newformat1 = date('Y-m-d',$timeLast);
        return $this->createQueryBuilder('h') 
        ->Where('h.id_customer = :id_customer')
        ->andWhere('h.dateYears BETWEEN :timeFirst AND :timeLast')
        ->setParameter('id_customer', $idCustomer)
        ->setParameter('timeFirst',$newformat)
        ->setParameter('timeLast',$newformat1)
        ->getQuery()
        ->getResult()
        ;
    }

    public function UpdateStatutHausseClient($idCustomer){
        $req = "UPDATE `hausse_indice_syntec_client` SET `status` = '3' WHERE `hausse_indice_syntec_client`.`id_customer` = ".$idCustomer." AND YEAR(date_years) = ".date("Y");
        $statement = $this->getEntityManager()->getConnection()->prepare($req);
        $statement->execute([]);
		return "okok";
    }

    public function getHausseByCustomerYearsCurent(int $id,$dateYears) {
        $req = "SELECT * FROM hausse_indice_syntec_client WHERE YEAR(date_years)>2021 and id_customer = 16";
        $qb = $this
        ->getEntityManager()
        ->createQuery("SELECT b FROM " . $this->getEntityName() . " b WHERE b.id_customer = $id AND b.dateYears >= '2023-01-01'");

		return $qb->getResult();
    }
}
