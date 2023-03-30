<?php

namespace App\Repository;

use App\Entity\Customer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Collection;
use PhpParser\Node\Expr\Cast\String_;

/**
 * @method Customer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Customer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Customer[]    findAll()
 * @method Customer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Customer::class);
    }

    public function findAllCustomer()
    {
        return $this->findBy(array(), array('id' => 'ASC'));
    }

    public function getMyAllCustomer ($user, int $rowpage = null, int $page = null) {
        $qb = $this->createQueryBuilder('c')
            ->join('c.user', 'u')
            ->where('u.id = :currentUser')
            ->setParameter('currentUser', $user)
            ->orderBy('c.id','DESC');

        if ($rowpage && $page){
            $qb->setMaxResults($rowpage)->setFirstResult($rowpage*($page-1));
        }

        return $qb->getQuery()->getResult();
    }

    public function getMyAllCustomerPourJuriste (int $rowpage = null, int $page = null) {
        $qb = $this->createQueryBuilder('c')
            ->orderBy('c.id','DESC');

        if ($rowpage && $page){
            $qb->setMaxResults($rowpage)->setFirstResult($rowpage*($page-1));
        }

        return $qb->getQuery()->getResult();
    }

    public function getMyAllCustomersansRow ($user) {
        if ($user == null){
            return $this->createQueryBuilder('c')
            ->orderBy('c.id','DESC')
            ->getQuery()
            ->getResult()
            //->setFirstResult($page)
            ;
        }
        return $this->createQueryBuilder('c')
            ->join('c.user', 'u')
            ->where('u.id = :currentUser')
            ->orderBy('c.id','DESC')
            ->setParameter('currentUser', $user)
            ->getQuery()
            ->getResult()
            //->setFirstResult($page)
            ;
    }
    public function getToutCustomerSansUser(){
        return $this->createQueryBuilder('c')
        ->select('c.raisonSocial','c.id')
        ->orderBy('c.id','DESC')
        ->getQuery()
        ->getResult()
        ;
    }
    public function getToutCustomer ($user) { 
        return $this->createQueryBuilder('c')
            ->select('c.raisonSocial')
            ->join('c.user', 'u')
            ->where('u.id = :currentUser')
            ->setParameter('currentUser', $user)
            ->orderBy('c.id','DESC')
            ->getQuery()
            ->getResult()
            ;
    }


    public function getcount($user){
        if ($user == null){
            return $this->createQueryBuilder('c')
            ->orderBy('c.id','DESC')
            ->select('count(c.id)')
            ->getQuery()
            ->getResult()
            ;
        }
        return $this->createQueryBuilder('c')
        ->join('c.user', 'u')
        ->where('u.id = :currentUser')
        ->setParameter('currentUser', $user)
        ->orderBy('c.id','DESC')
        ->select('count(c.id)')
        ->getQuery()
        ->getResult()
        ;
    }

    public function getMyAllRaisonSocialCustomer($user){
        
        return $this->createQueryBuilder('c')
            ->select('c.raisonSocial','c.marqueCommercial')
            ->join('c.user', 'u')
            ->where('u.id = :currentUser')
            ->setParameter('currentUser', $user)
            ->orderBy('c.id','DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function searchV2($user,$raison,string $contact,string $nbdc,string $marque,string $category,string $map){
        $result=$this->createQueryBuilder('c')
            ->join('c.user', 'u')
            ->where('u.id = :currentUser')
            ->orderBy('c.id','DESC');
        if($raison != "empty"){
            $result = $result->andWhere('c.raisonSocial = :raison');
        }
        if($contact !="empty"){
            $result =$result
            ->join('c.contacts', 't')
            ->andWhere('t.nom LIKE :keyword');
        }
        if($nbdc != "empty"){
            $result =$result
            ->join('c.resumeLeads', 'r')
            ->join('r.bdcs', 'b')
            ->andWhere('b.numBdc LIKE :keywordbdc');
        }
        if($marque != "empty"){
            $result =$result
            ->andWhere('c.marqueCommercial LIKE :keywordMarque');
        }
        if($category != "empty"){
            $result =$result
            ->join('c.categorieClient','ctclient')
            ->andWhere('ctclient.libelle LIKE :keywordKtCl');
        }
        if($map != "empty"){
            $result =$result
            ->join('c.mappingClient','mpclient')
            ->andWhere('mpclient.libelle LIKE :keywordmpcl');
        }
        //setParametre
        if($raison != "empty"){
            $result=$result
            ->setParameter('raison', $raison);
        }
        if($contact !="empty"){
            $result=$result
            ->setParameter('keyword', '%'.$contact.'%');
        }
        if($nbdc != "empty"){
            $result=$result
            ->setParameter('keywordbdc', '%'.$nbdc.'%');
        }
        if($marque !="empty"){
            $result =$result
            ->setParameter('keywordMarque', $marque);
        }
        if($category != "empty"){
            $result =$result
            ->setParameter('keywordKtCl', $category);
        }
        if($map != "empty"){
            $result =$result
            ->setParameter('keywordmpcl', $map);
        }
        $result = $result->setParameter('currentUser', $user);
        $result = $result->getQuery()->getResult();
        return $result;
    }
    public function searchV2withRow(int $rowpage,int $page,$user,$raison,string $contact,string $nbdc,string $marque,string $category,string $map){
        $result=$this->createQueryBuilder('c')
            ->join('c.user', 'u')
            ->where('u.id = :currentUser')
            ->orderBy('c.id','DESC');
        if($raison != "empty"){
            $result = $result->andWhere('c.raisonSocial = :raison');
        }
        if($contact !="empty"){
            $result =$result
            ->join('c.contacts', 't')
            ->andWhere('t.nom LIKE :keyword');
        }
        if($nbdc != "empty"){
            $result =$result
            ->join('c.resumeLeads', 'r')
            ->join('r.bdcs', 'b')
            ->andWhere('b.numBdc LIKE :keywordbdc');
        }
        if($marque != "empty"){
            $result =$result
            ->andWhere('c.marqueCommercial LIKE :keywordMarque');
        }
        if($category != "empty"){
            $result =$result
            ->join('c.categorieClient','ctclient')
            ->andWhere('ctclient.libelle LIKE :keywordKtCl');
        }
        if($map != "empty"){
            $result =$result
            ->join('c.mappingClient','mpclient')
            ->andWhere('mpclient.libelle LIKE :keywordmpcl');
        }
        //setParametre
        if($raison != "empty"){
            $result=$result
            ->setParameter('raison', $raison);
        }
        if($contact !="empty"){
            $result=$result
            ->setParameter('keyword', '%'.$contact.'%');
        }
        if($nbdc != "empty"){
            $result=$result
            ->setParameter('keywordbdc', '%'.$nbdc.'%');
        }
        if($marque !="empty"){
            $result =$result
            ->setParameter('keywordMarque', $marque);
        }
        if($category != "empty"){
            $result =$result
            ->setParameter('keywordKtCl', $category);
        }
        if($map != "empty"){
            $result =$result
            ->setParameter('keywordmpcl', $map);
        }
        $result = $result->setParameter('currentUser', $user)
            ->setMaxResults($rowpage)
            ->setFirstResult($rowpage*($page-1));
        $result = $result->getQuery()->getResult();
        return $result;
    }

    

    /*
    public function findOneBySomeField($value): ?Customer
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function searchByKeyword($user,string $filter, string $keyword,$rowpage,$page): array
    {
        if ($filter == "raisonSocial") {
            return $this->createQueryBuilder('c')
                ->join('c.user', 'u')
                ->where('u.id = :currentUser')
                ->andWhere('c.raisonSocial LIKE :keyword')
                ->setParameter('keyword', '%'.$keyword.'%')
                ->setParameter('currentUser', $user)
                ->setMaxResults($rowpage)
                ->setFirstResult($rowpage*($page-1))
                ->getQuery()
                ->getResult();
        } elseif ($filter == "numBdc") {
            return $this->createQueryBuilder('c')
                ->join('c.user', 'u')
                ->where('u.id = :currentUser')
                ->join('c.resumeLeads', 'r')
                ->join('r.bdcs', 'b')
                ->andWhere('b.numBdc LIKE :keyword')
                ->setParameter('keyword', '%'.$keyword.'%')
                ->setParameter('currentUser', $user)
                ->setMaxResults($rowpage)
                ->setFirstResult($rowpage*($page-1))
                ->getQuery()
                ->getResult();
        } else {
            return $this->createQueryBuilder('c')
                ->join('c.contacts', 't')
                ->orWhere('t.nom LIKE :keyword')
                ->orWhere('t.tel LIKE :keyword')
                ->orWhere('t.prenom LIKE :keyword')
                ->setParameter('keyword', '%'.$keyword.'%')
                ->setMaxResults($rowpage)
                ->setFirstResult($rowpage*($page-1))
                ->getQuery()
                ->getResult();
        }
    }
    public function searchByKeywordPourJuriste(string $filter, string $keyword,$rowpage,$page): array
    {
        if ($filter == "raisonSocial") {
            return $this->createQueryBuilder('c')
                ->andWhere('c.raisonSocial LIKE :keyword')
                ->setParameter('keyword', '%'.$keyword.'%')
                ->setMaxResults($rowpage)
                ->setFirstResult($rowpage*($page-1))
                ->getQuery()
                ->getResult();
        } elseif ($filter == "numBdc") {
            return $this->createQueryBuilder('c')
                ->join('c.resumeLeads', 'r')
                ->join('r.bdcs', 'b')
                ->andWhere('b.numBdc LIKE :keyword')
                ->setParameter('keyword', '%'.$keyword.'%')
                ->setMaxResults($rowpage)
                ->setFirstResult($rowpage*($page-1))
                ->getQuery()
                ->getResult();
        } else {
            return $this->createQueryBuilder('c')
                ->join('c.contacts', 't')
                ->orWhere('t.nom LIKE :keyword')
                ->orWhere('t.tel LIKE :keyword')
                ->orWhere('t.prenom LIKE :keyword')
                ->setParameter('keyword', '%'.$keyword.'%')
                ->setMaxResults($rowpage)
                ->setFirstResult($rowpage*($page-1))
                ->getQuery()
                ->getResult();
        }
    }
}
