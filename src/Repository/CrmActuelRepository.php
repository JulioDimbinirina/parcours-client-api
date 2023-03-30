<?php

namespace App\Repository;

use App\Entity\Devise;
use App\Models\Client;
use App\Service\SecondDatabaseConnexion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Devise|null find($id, $lockMode = null, $lockVersion = null)
 * @method Devise|null findOneBy(array $criteria, array $orderBy = null)
 * @method Devise[]    findAll()
 * @method Devise[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CrmActuelRepository extends ServiceEntityRepository
{
    private $connexion;

    public function __construct(SecondDatabaseConnexion $databaseConnexion)
    {
        $this->connexion = $databaseConnexion->connexion();
    }

    public function getSiteOfProduction(){
        return $this->connexion->fetchAllAssociative('SELECT DISTINCT `site_de_production` FROM fq');
    }

    public function getSocieteForOneSiteOfProduction($siteProd, $param){
        $req = 'SELECT DISTINCT raison_sociale FROM fq WHERE site_de_production = "'. $siteProd .'" LIMIT '. $param;
        return $this->connexion->fetchAllAssociative($req);
    }

    public function getDataCustomerInCrmActuel($societe){
        $req = "SELECT societe, adresse, cp, ville, tel_standard, civilite, prenom,
                nom, fonction, telephone,mail, skype, prospect_suspect, pays FROM
                `client` WHERE `societe` = '". $societe ."' LIMIT 1";

        return $this->connexion->fetchAllAssociative($req);
    }

    public function getAllCustomerInCrmActuel(){
        $req = "SELECT societe, adresse, cp, ville, tel_standard, civilite, prenom,
                nom, fonction, telephone,mail, skype, prospect_suspect, pays FROM
                client where prospect_suspect is not null";

        return $this->connexion->fetchAllAssociative($req);
    }

    public function injectOrUpdateCustomerInCrmActuel($donnees, $contact, bool $isUpdateMode = false){
        $client = new Client($this->connexion);

        if (!empty($donnees) && !empty($contact)) {
            $client->societe = $donnees['raisonSocial'];
            $client->adresse = $donnees['adresse'];
            $client->cp = $donnees['cp'];
            $client->tel_standard = $donnees['tel'];
            $client->ville = $donnees['ville'];
            $client->prospect_suspect = $this->getValueOfCategorieClient($donnees['categorieClientId']);
            $client->civilite = $contact['civilite'];
            $client->prenom = $contact['prenom'];
            $client->nom = $contact['nom'];
            $client->fonction = $contact['fonction'];
            $client->telephone = $contact['tel'];
            $client->mail = $contact['email'];
            $client->skype = $contact['skype'] ?? null;
            $isUpdateMode && $client->mailForContactToUpdateOrDelete = $contact['mailForContactToUpdateOrDelete'];

            $returnValue = false;

            if ($isUpdateMode){
                if($client->update()){
                    $returnValue =  true;
                }
            } else {
                if($client->create()){
                    $returnValue =  true;
                }
            }

            return $returnValue;
        }
    }

    public function deleteCustomerInCrmActuel(string $emailForContact){
        # On instancie le client
        $client = new Client($this->connexion);

        if ($emailForContact){
            $client->mailForContactToUpdateOrDelete = $emailForContact;

            if ($client->delete()){
                return true;
            }
            return false;
        }
    }

    /**
     * @param mixed $username
     * @return CrmActuelRepository
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @param mixed $password
     * @return CrmActuelRepository
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @param mixed $dbname
     * @return CrmActuelRepository
     */
    public function setDbname($dbname)
    {
        $this->dbname = $dbname;
        return $this;
    }

    /**
     * @param mixed $host
     * @return CrmActuelRepository
     */
    public function setHost($host)
    {
        $this->host = $host;
        return $this;
    }

    private function getValueOfCategorieClient($categoryClient) {
        switch ($categoryClient)
        {
            case '1':
                return "prospect";
                break;
            case '2':
                return "client";
                break;
            case '3':
                return "client perdu";
                break;
            default:
                return "prospect";
        }
    }
}