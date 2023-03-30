<?php

namespace App\Repository;

use App\Entity\Bdc;
use App\Entity\User;
use App\Service\GetStatutLeadViaRole;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method Bdc|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bdc|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bdc[]    findAll()
 * @method Bdc[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BdcRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bdc::class);
    }

    public function GetBdcById(int $id){
        $qb = $this
        ->getEntityManager()
        ->createQuery("SELECT b FROM " . $this->getEntityName()." b WHERE b.id =$id")
        ;
		return $qb->getResult();
    } 

    public function GetBdcById2(int $id){
        $rawSql = "SELECT m FROM bdc AS m where m.id = $id";
        $stmt = $this->getEntityManager()->getConnection()->prepare($rawSql);
        $stmt->execute([]);
    
        return $stmt->fetchAll();
    }

    public function UpdateIdSigneClient(Bdc $bdc){
        $bdc->setSignaturePackageId($bdc->getSignaturePackageComId());
        $this->_em->flush();
    }
    public function comparaisonBdcOperation($i,$tab2,$tab3,$fotoana,$libel){
        if($tab3[$i]->getPrixUnit() != $tab2[$i]->getPrixUnit() ){
            $r3=$tab3[$i]->getPrixUnit();
            $r2=$tab2[$i]->getPrixUnit();
            $tableaustring="Le $fotoana ,Modification tarif de l operation $libel  de $r2 € euros  en $r3 € euros ";
            return $tableaustring;
        }
    }
    public function  indexage($result2){
        $tab2=array();
        $i2=0;
        foreach ($result2 as $r){
            $tab2[$i2]=$r;
            $i2++;
        }
        return $tab2;
    }
    public function getVersionBdc($bd){
        $reponse="";
        $version= explode("_",$bd->getNumVersion());
        $i=0;
        foreach ($version as $v){
            if($i ==1 )
                $reponse .=  substr($v,1);
            $i++;
        }
        return $reponse;
    }
    public function getBdcByVersion($version,$tab){
        foreach ($tab as $t){
            if($version == $this->getVersionBdc($t)){
                return $t;
            }
        }
        return null;
    }

    public function getMyBdc (int $user) {
        return $this->createQueryBuilder('b')
            ->join('b.resumeLead', 'r')
            ->join('r.customer', 'c')
            ->join('c.user', 'u')
            ->where('u.id = :currentUser')
            ->andWhere('b.numBdc is not null')
            ->setParameter('currentUser', $user)
            ->getQuery()
            ->getResult()
            ;
    }

    public function getMyNumBdc(int $userId){
        return $this->createQueryBuilder('b')
            ->select('DISTINCT b.numBdc')
            ->join('b.resumeLead', 'r')
            ->join('r.customer', 'c')
            ->join('c.user', 'u')
            ->where('u.id = :currentUser')
            ->andWhere('b.numBdc is not null')
            ->setParameter('currentUser', $userId)
            ->getQuery()
            ->getResult()
            ;
    }

    public function getLibelleOfSociFact(int $userId){
        return $this->createQueryBuilder('b')
            ->select('DISTINCT s.libelle')
            ->join('b.resumeLead', 'r')
            ->join('r.customer', 'c')
            ->join('c.user', 'u')
            ->join('b.societeFacturation', 's')
            ->where('u.id = :currentUser')
            ->andWhere('b.numBdc is not null')
            ->setParameter('currentUser', $userId)
            ->getQuery()
            ->getResult()
            ;
    }

    public function getCommercialsBdcs($role){
        $statutBdcValidator = new GetStatutLeadViaRole();

        list($statut, $statut1) = $statutBdcValidator->getStatut($role);

        return $this->createQueryBuilder('b')
            ->select('u.email')
            ->join('b.resumeLead', 'r')
            ->join('r.customer', 'c')
            ->join('c.user', 'u')
            ->andWhere('b.numBdc is not null')
            ->andwhere('b.statutLead = :statut')
            ->orWhere('b.statutLead = :statut1')
            ->setParameter('statut', $statut)
            ->setParameter('statut1', $statut1)
            ->distinct()
            ->getQuery()
            ->getResult()
            ;
    }

    public function getBdcViaStatutLead (int $user, int $statut = null) {
        return $this->createQueryBuilder('b')
            ->join('b.resumeLead', 'r')
            ->join('r.customer', 'c')
            ->join('c.user', 'u')
            ->where('u.id = :currentUser')
            ->andwhere('b.statutLead = :statut')
            ->andWhere('b.numBdc is not null')
            ->setParameter('currentUser', $user)
            ->setParameter('statut', $statut)
            ->getQuery()
            ->getResult()
            ;
    }

    public function getBdcByIdMere(int $idm){
        return $this->createQueryBuilder('b')
            ->where('b.idMere = :idm')
            ->setParameter('idm', $idm)
            ->getQuery()
            ->getResult()
            ;
    }

    public function getBdcViaCustomerAndPaysProd($rs, $paysProd){
        return $this->createQueryBuilder('b')
            ->join('b.resumeLead', 'r')
            ->join('r.customer', 'c')
            ->join('b.paysProduction', 'p')
            ->Where('c.raisonSocial = :client')
            ->andWhere('p.libelle = :paysprod')
            ->setParameter('client', $rs)
            ->setParameter('paysprod', $paysProd)
            ->getQuery()
            ->getResult();
    }

    public function countBdcByStatutLead($user){
        $query = $this->createQueryBuilder('b')
            ->select('Max(b.id)')
            ->innerJoin('b.resumeLead', 'r')
            ->innerJoin('r.customer', 'c')
            ->innerJoin('c.user', 'u')
            ->where('u.id = :currentUser')
            ->andWhere('b.statutLead is not null')
            ->groupBy('b.idMere')
            ->setParameter('currentUser', $user->getId())
            ->getQuery()
            ->getArrayResult();

        return $this->createQueryBuilder('bd')
            ->select('bd.statutLead, Count(bd.id) as nombre')
            ->where('bd.id IN (:subQuery)')
            ->setParameter('subQuery', $query)
            ->groupBy('bd.statutLead')
            ->getQuery()
            ->getResult();
    }

    public function getBdcByValidator($validator,$idPaysProduction){
        $query = $this->createQueryBuilder('b')
        ->select('Max(b.id), b.numBdc')
        ->where('b.numBdc is not null');
        switch ($validator) {
            case "DIRPROD":
                $query->join('b.paysProduction', 'p')
                    ->andWhere('b.statutLead = :statut OR b.statutLead = :statut2')
                    ->andWhere('p.id = :pays')
                    ->setParameter('statut', 3)
                    ->setParameter('statut2', 13)
                    ->setParameter('pays', $idPaysProduction);
                break;
            case "DIRFINANCE":
                $query->andWhere('b.statutLead = :statut OR b.statutLead = :statut2')
                    ->setParameter('statut', 4)
                    ->setParameter('statut2', 14);
                break;
            case "DIRDG":
                $query->andWhere('b.statutLead = :statut1  OR b.statutLead = :statut3')
                    ->setParameter('statut1', 6)
                    ->setParameter('statut3', 16);
                break;
        }

        $query->orderBy('b.id', 'DESC')->groupBy('b.idMere');
       
        return $query ->getQuery()->getResult();
    }

    public function getBdcList($user, $dataFront){
        $query = $this->createQueryBuilder('b')
            ->select('Max(b.id)')
            ->where('b.numBdc is not null');

        # Filtre des bdcs selon le rôle de l'utilisateur
        switch ($user->getRoles()[0]) {
            case "ROLE_USER":
                $query->join('b.resumeLead', 'r')
                    ->join('r.customer', 'c')
                    ->join('c.user', 'u')
                    ->andWhere('u.id = :currentUser')
                    ->setParameter('currentUser', $user->getId());
                break;
            case "ROLE_DIRPROD":
                $query->join('b.paysProduction', 'p')
                    ->andWhere('b.statutLead = :statut OR b.statutLead = :statut2')
                    ->andWhere('p.id = :pays')
                    ->setParameter('statut', 3)
                    ->setParameter('statut2', 13)
                    ->setParameter('pays', $user->getPaysProduction()->getId());
                break;
            case "ROLE_FINANCE":
                $query->andWhere('b.statutLead = :statut OR b.statutLead = :statut2')
                    ->setParameter('statut', 4)
                    ->setParameter('statut2', 14);
                break;
            case "ROLE_DG":
                $query->andWhere('b.statutLead = :statut1 OR b.statutLead = :statut2 OR b.statutLead = :statut3 OR b.statutLead = :statut4')
                    ->setParameter('statut1', 6)
                    ->setParameter('statut2', 8)
                    ->setParameter('statut3', 16)
                    ->setParameter('statut4', 18);
                break;
        }

        # Filtre par statutlead
        /* if (isset($dataFront["statutLead"]) && !empty($dataFront["statutLead"])){
            $nbStatut = count($dataFront["statutLead"]);

            switch ($nbStatut){
                case 1:
                    $query->andWhere('b.statutLead = :statutLead')
                        ->setParameter('statutLead', $dataFront["statutLead"][0]);
                    break;
                case 2:
                    $query->andWhere('b.statutLead = :statutLead OR b.statutLead = :statutLead2')
                        ->setParameter('statutLead', $dataFront["statutLead"][0])
                        ->setParameter('statutLead2', $dataFront["statutLead"][1]);
                    break;
            }
        } */

        # Filtre entre deux dates
        if (isset($dataFront["dateDebut"]) && !empty($dataFront["dateDebut"]) && isset($dataFront["dateFin"]) && !empty($dataFront["dateFin"])){
            $query->andWhere('b.dateCreate BETWEEN :date1 AND :date2')
                ->setParameter('date1', $dataFront["dateDebut"])
                ->setParameter('date2', $dataFront["dateFin"]);

        }

        # Filtre par selected input
        if (isset($dataFront["tabStatut"]) && !empty($dataFront["tabStatut"])){
            foreach ($dataFront["tabStatut"] as $tab) {
                $query->andwhere('b.statutLead = :statut')
                    ->setParameter('statut', $tab);
            }
        }

        # Filtre par mot clé de recherche
        if (!empty($dataFront["selectFilter"]) && !empty($dataFront["keyword"])){
            switch ($dataFront["selectFilter"]){
                case "numBdc":
                    $query->andWhere('b.numBdc like :numBdc')
                        ->setParameter('numBdc', '%'.$dataFront["keyword"].'%');
                    break;
                case "raisonSocial":
                    $query->andWhere('c.raisonSocial like :raisonSocial')
                        ->setParameter('raisonSocial', '%'.$dataFront["keyword"].'%');
                    break;
                case "contact":
                    $query->join('c.contacts', 't')
                        ->andWhere('t.nom like :word')
                        ->orWhere('t.prenom like :word')
                        ->orWhere('t.tel like :word')
                        ->orWhere('t.email like :word')
                        ->setParameter('word', '%'.$dataFront["keyword"].'%');
                    break;
            }
        }

        # Filtre numero bdc via input dans la table
        if (!empty($dataFront["numbdcSearch"])){
            $query->andWhere('b.numBdc like :word')->setParameter('word', '%'.$dataFront["numbdcSearch"].'%');
        }

        # Filtre pays de production via input dans la table
        if (!empty($dataFront["paydprodSearch"])){
            $query->join('b.paysProduction', 'p')
                ->andWhere('p.libelle like :word')
                ->setParameter('word', '%'.$dataFront["paydprodSearch"].'%');
        }

        # Filtre raison sociale via input dans la table
        if (!empty($dataFront["clientSearch"])){
            $query->andWhere('c.raisonSocial like :word')
                ->setParameter('word', '%'.$dataFront["clientSearch"].'%');
        }

        # Filtre duree de traitement via input dans la table
        if (!empty($dataFront["dmtSearch"])){
            foreach ($dataFront["dmtSearch"] as $dmt) {
                $query->join('r.dureeTrt', 'd')
                    ->andWhere('d.libelle like :word')
                    ->setParameter('word', '%'.$dmt.'%');
            }
        }

        # Filtre societe de facturation via input dans la table
        if (!empty($dataFront["sociFactSearch"])){
            $query->join('b.societeFacturation', 's')
                ->andWhere('s.libelle like :word')
                ->setParameter('word', '%'.$dataFront["sociFactSearch"].'%');
        }

        # Filtre societe de facturation via input dans la table
        if (!empty($dataFront["dateCreateSearch"])){
            $query->andWhere('b.dateCreate like :word')
                ->setParameter('word', '%'.$dataFront["dateCreateSearch"].'%');
        }

        $query->groupBy('b.idMere');

        $res = $query->getQuery()->getResult();

        $total = count($res);

        $bdcs = $this->createQueryBuilder('bd')
            ->where('bd.id IN (:qb)')
            ->setParameter('qb', $res)
            ->orderBy('bd.id', 'DESC')
            // ->setFirstResult(($dataFront["rowPerPage"] * $dataFront["page"]) - $dataFront["rowPerPage"])
            // ->setMaxResults($dataFront["rowPerPage"])
            ->getQuery()
            ->getResult();

        return [$total, $bdcs];
    }

    public function getAllLastBdcByIdMere(){
        $query = $this->createQueryBuilder('b')
        ->select('Max(b.id)')
        ->where('b.numBdc is not null');
        $query->groupBy('b.idMere');
        $res = $query->getQuery()->getResult();
        $bdcs = $this->createQueryBuilder('bd')
            ->where('bd.id IN (:qb)')
            ->setParameter('qb', $res)
            ->orderBy('bd.id', 'DESC')
            // ->setFirstResult(($dataFront["rowPerPage"] * $dataFront["page"]) - $dataFront["rowPerPage"])
            // ->setMaxResults($dataFront["rowPerPage"])
            ->getQuery()
            ->getResult();

        $res = $query->getQuery()->getResult();

        return $bdcs;
    }

    public function counterBdcByStatulead(int $userId = null, $tabOfStatutLead = null){
        $nbStatut = count($tabOfStatutLead);

        $results = null;

        switch ($nbStatut) {
            case 1:
                $results = $this->createQueryBuilder('b')
                    ->select('DISTINCT b.idMere')
                    ->join('b.resumeLead', 'r')
                    ->join('r.customer', 'c')
                    ->join('c.user', 'u')
                    ->where('u.id = :currentUser')
                    ->andWhere('b.numBdc is not null')
                    ->andWhere('b.statutLead = :statut1')
                    ->setParameter('currentUser', $userId)
                    ->setParameter('statut1', $tabOfStatutLead[0])
                    ->getQuery()
                    ->getResult();
                break;
            case 2:
                $results = $this->createQueryBuilder('b')
                    ->select('DISTINCT b.idMere')
                    ->join('b.resumeLead', 'r')
                    ->join('r.customer', 'c')
                    ->join('c.user', 'u')
                    ->where('u.id = :currentUser')
                    ->andWhere('b.numBdc is not null')
                    ->andWhere('b.statutLead = :statut1')
                    ->orWhere('b.statutLead = :statut2')
                    ->setParameter('currentUser', $userId)
                    ->setParameter('statut1', $tabOfStatutLead[0])
                    ->setParameter('statut2', $tabOfStatutLead[1])
                    ->getQuery()
                    ->getResult();
                break;
        }

        return count($results);
    }

    public function findAllBdcEnProduction(){
        return $this->createQueryBuilder('b')
            ->where('b.statutLead = :statut')
            ->orWhere('b.statutLead = :statut2')
            ->setParameter('statut', 12)
            ->setParameter('statut2', 22)
            ->orderBy( 'b.id','DESC')
            ->getQuery()
            ->getResult()
            ;
    }

     public function findByUniqId ($value) {

        return $this->createQueryBuilder('b')
            ->andWhere('b.uniqId = :uniqId')
            ->setParameter('uniqId', $value)
            ->getQuery()
            ->getResult()
        ;
    }

    public function GetBdcByUniqId ($id){
        $qb = $this
        ->getEntityManager()
        ->createQuery("SELECT b FROM " . $this->getEntityName()." b WHERE b.uniqId = '$id'")
        ;
		return $qb->getResult();
    }

    public function getBdcForOneCustomer ($idCustomer) {
        return $this->createQueryBuilder('b')
            ->join('b.resumeLead', 'r')
            ->join('r.customer', 'c')
            ->where('c.id = :customer')
            ->setParameter('customer', $idCustomer)
            ->getQuery()
            ->getResult()
            ;
    }
    public function getBdcForOneCustomer2 ($idCustomer) {
        return $this->createQueryBuilder('b')
            ->join('b.resumeLead', 'r')
            ->join('r.customer', 'c')
            ->where('c.id = :customer')
            ->andWhere('b.statutLead = :status OR b.statutLead = :status2')
            ->setParameter('customer', $idCustomer)
            ->setParameter('status', 22)
            ->setParameter('status2', 12)
            ->getQuery()
            ->getResult()
            ;
    }
    public function getBdcForHausseUpdate ($idCustomer) {
        return $this->createQueryBuilder('b')
            ->join('b.resumeLead', 'r')
            ->join('r.customer', 'c')
            ->where('c.id = :customer')
            ->andWhere('b.statutLead = :status')
            ->setParameter('customer', $idCustomer)
            ->setParameter('status', 22)
            ->getQuery()
            ->getResult()
            ;
    }
    public function getBdcForOneCustomer3 ($idCustomer) {
        return $this->createQueryBuilder('b')
            ->join('b.resumeLead', 'r')
            ->join('r.customer', 'c')
            ->where('c.id = :customer')
            ->andWhere('b.statutLead = :status OR b.statutLead = :status2 OR b.statutLead = :status3')
            ->groupBy('b.idMere')
            ->setParameter('customer', $idCustomer)
            ->setParameter('status', 22)
            ->setParameter('status2', 12)
            ->setParameter('status3', 20)
            ->getQuery()
            ->getResult()
            ;
    }
	public function findByNot( array $criteria, array $orderBy = null, $limit = null, $offset = null )
    {
        $qb = $this->createQueryBuilder('b');
        $expr = $this->getEntityManager()->getExpressionBuilder();

        foreach ( $criteria as $field => $value ) {

            $qb->andWhere( $expr->neq( 'b.' . $field, $value ) );
        }
		
        return $qb->getQuery()
            ->getResult();
    }

	public function findBdcToSign()
	{
		$qb = $this->getEntityManager()
        ->createQuery("SELECT b FROM " . $this->getEntityName() . " b WHERE b.signaturePackageId IS NOT NULL AND b.signaturePackageId != '' AND (b.statutLead = 10 OR b.statutLead = 20)");

		return $qb->getResult();
	}

	public function findBdcToSignInterne()
	{
		$qb = $this
        ->getEntityManager()
        ->createQuery("SELECT b FROM " . $this->getEntityName() . " b WHERE b.signaturePackageComId IS NOT NULL AND b.signaturePackageComId != '' AND b.statutLead = 8 OR b.statutLead = 18");

		return $qb->getResult();
	}

    public function deleteByResumeLeadId(int $id) {
        return $this->createQueryBuilder('b')
            ->delete()
            ->where('b.resumeLead = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getResult();
    }

    public function deleteBdcViaIdResumeLead(int $idResumeLead) {
        $req = 'START TRANSACTION; SET FOREIGN_KEY_CHECKS=0; DELETE FROM bdc WHERE resume_lead_id = "'.$idResumeLead.'"; SET FOREIGN_KEY_CHECKS=1; COMMIT;';
        $statement = $this->getEntityManager()->getConnection()->prepare($req);
        $statement->execute([]);
    }

    public function getBdcLastAndNotVersion ($param) {
        return $this->createQueryBuilder('b')
            ->where('b.idMere = :val')
            ->setParameter('val', $param)
            ->orderBy('b.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    public function getBdcEnProduction ($param) {
        return $this->createQueryBuilder('b')
            ->where('b.statutLead = :val')
            ->setParameter('val', $param)
            ->getQuery()
            ->getResult()
            ;
    }

    public function getIdByIdM($idm){
        return $this->createQueryBuilder('b')
            ->where('b.idMere = :val')
            ->setParameter('val', $idm)
            ->getQuery()
            ->getResult()
            ;
    }
}
