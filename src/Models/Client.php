<?php
namespace App\Models;

class Client{
    private $connexion;
    private $table = "client";

    # Propriétés
    public $societe;
    public $adresse;
    public $cp;
    public $tel_standard;
    public $ville;
    public $civilite;
    public $prenom;
    public $nom;
    public $fonction;
    public $telephone;
    public $mail;
    public $skype;
    public $niveau;
    public $id;
    public $commercial_affilier;
    public $besoins_du_contact;
    public $precisions_du_besoin;
    public $provenance_du_contact;
    public $prospect_suspect;
    public $membre_codir_contact;
    public $Drap;
    public $societe_f;
    public $civilite_f;
    public $nom_f;
    public $prenom_f;
    public $mail_f;
    public $cp_f;
    public $ville_f;
    public $adresse_f;
    public $service_f;
    public $copie_mail_f;
    public $mail_livraison;
    public $ftp;
    public $ip;
    public $login;
    public $mdp;
    public $telbur;
    public $telmob;
    public $code_client;
    public $flag;
    public $mailForContactToUpdateOrDelete;

    # Connexion avec $db pour la connexion à la base de donnée
    public function __construct($db)
    {
        $this->connexion = $db;
    }

    public function create(){
        $sql = "INSERT INTO " . $this->table . " SET societe=:societe, adresse=:adresse, cp=:cp, tel_standard=:tel_standard, ville=:ville,
        civilite=:civilite, prenom=:prenom, nom=:nom, fonction=:fonction, telephone=:telephone, mail=:mail, skype=:skype,
        niveau=:niveau, commercial_affilier=:commercial_affilier, besoins_du_contact=:besoins_du_contact, precisions_du_besoin=:precisions_du_besoin,
        provenance_du_contact=:provenance_du_contact, prospect_suspect=:prospect_suspect, membre_codir_contact=:membre_codir_contact, Drap=:Drap,
        societe_f=:societe_f, civilite_f=:civilite_f, nom_f=:nom_f, prenom_f=:prenom_f, mail_f=:mail_f, cp_f=:cp_f, ville_f=:ville_f, adresse_f=:adresse_f,
        service_f=:service_f, copie_mail_f=:copie_mail_f, mail_livraison=:mail_livraison, ftp=:ftp, ip=:ip, login=:login, mdp=:mdp, telbur=:telbur,
        telmob=:telmob, code_client=:code_client, flag=:flag";

        return $this->setAttributesAndExecuteQuery($sql);
    }

    public function update(){
        $sql = "UPDATE " . $this->table . " SET societe=:societe, adresse=:adresse, cp=:cp, tel_standard=:tel_standard, ville=:ville,
        civilite=:civilite, prenom=:prenom, nom=:nom, fonction=:fonction, telephone=:telephone, mail=:mail, skype=:skype,
        niveau=:niveau, commercial_affilier=:commercial_affilier, besoins_du_contact=:besoins_du_contact, precisions_du_besoin=:precisions_du_besoin,
        provenance_du_contact=:provenance_du_contact, prospect_suspect=:prospect_suspect, membre_codir_contact=:membre_codir_contact, Drap=:Drap,
        societe_f=:societe_f, civilite_f=:civilite_f, nom_f=:nom_f, prenom_f=:prenom_f, mail_f=:mail_f, cp_f=:cp_f, ville_f=:ville_f, adresse_f=:adresse_f,
        service_f=:service_f, copie_mail_f=:copie_mail_f, mail_livraison=:mail_livraison, ftp=:ftp, ip=:ip, login=:login, mdp=:mdp, telbur=:telbur,
        telmob=:telmob, code_client=:code_client, flag=:flag WHERE mail = :mailForContactToUpdateOrDelete";

        return $this->setAttributesAndExecuteQuery($sql, true);
    }

    public function delete(){
        # On écrit la requête
        $sql = "DELETE FROM " . $this->table . " WHERE mail = ?";

        # On prépare la requête
        $query = $this->connexion->prepare($sql);

        # On sécurise les données
        $this->id = htmlspecialchars(strip_tags($this->id));

        # On attache l'id
        $query->bindParam(1, $this->mailForContactToUpdateOrDelete);

        # On exécute la requête
        if($query->execute()){
            return true;
        }

        return false;
    }

    private function setAttributesAndExecuteQuery($sql, bool $isUpdateQuery = false): bool
    {
        $query = $this->connexion->prepare($sql);

        $this->societe = htmlspecialchars(strip_tags($this->societe));
        $this->adresse = htmlspecialchars(strip_tags($this->adresse));
        $this->cp = htmlspecialchars(strip_tags($this->cp));
        $this->tel_standard = htmlspecialchars(strip_tags($this->tel_standard));
        $this->ville = htmlspecialchars(strip_tags($this->ville));
        $this->prospect_suspect = htmlspecialchars(strip_tags($this->prospect_suspect));
        $this->civilite = htmlspecialchars(strip_tags($this->civilite));
        $this->prenom = htmlspecialchars(strip_tags($this->prenom));
        $this->nom = htmlspecialchars(strip_tags($this->nom));
        $this->fonction = htmlspecialchars(strip_tags($this->fonction));
        $this->telephone = htmlspecialchars(strip_tags($this->telephone));
        $this->mail = htmlspecialchars(strip_tags($this->mail));
        $this->skype = htmlspecialchars(strip_tags($this->skype));
        $this->niveau = null;
        $this->commercial_affilier = null;
        $this->besoins_du_contact = null;
        $this->precisions_du_besoin = null;
        $this->provenance_du_contact = null;
        $this->membre_codir_contact = null;
        $this->Drap = null;
        $this->societe_f = null;
        $this->civilite_f = null;
        $this->nom_f = null;
        $this->prenom_f = null;
        $this->mail_f = null;
        $this->cp_f = null;
        $this->ville_f = null;
        $this->adresse_f = null;
        $this->service_f = null;
        $this->copie_mail_f = null;
        $this->mail_livraison = null;
        $this->ftp = null;
        $this->ip = null;
        $this->login = null;
        $this->mdp = null;
        $this->telbur = null;
        $this->telmob = null;
        $this->code_client = null;
        $this->flag = null;

        # Pour la modification
        $isUpdateQuery && $this->mailForContactToUpdateOrDelete = htmlspecialchars(strip_tags($this->mailForContactToUpdateOrDelete));

        $query->bindParam(":societe", $this->societe);
        $query->bindParam(":adresse", $this->adresse);
        $query->bindParam(":cp", $this->cp);
        $query->bindParam(":tel_standard", $this->tel_standard);
        $query->bindParam(":ville", $this->ville);
        $query->bindParam(":civilite", $this->civilite);
        $query->bindParam(":prenom", $this->prenom);
        $query->bindParam(":nom", $this->nom);
        $query->bindParam(":fonction", $this->fonction);
        $query->bindParam(":telephone", $this->telephone);
        $query->bindParam(":mail", $this->mail);
        $query->bindParam(":skype", $this->skype);
        $query->bindParam(":niveau", $this->niveau);
        $query->bindParam(":commercial_affilier", $this->commercial_affilier);
        $query->bindParam(":besoins_du_contact", $this->besoins_du_contact);
        $query->bindParam(":precisions_du_besoin", $this->precisions_du_besoin);
        $query->bindParam(":provenance_du_contact", $this->provenance_du_contact);
        $query->bindParam(":prospect_suspect", $this->prospect_suspect);
        $query->bindParam(":membre_codir_contact", $this->membre_codir_contact);
        $query->bindParam(":Drap", $this->Drap);
        $query->bindParam(":societe_f", $this->societe_f);
        $query->bindParam(":civilite_f", $this->civilite_f);
        $query->bindParam(":nom_f", $this->nom_f);
        $query->bindParam(":prenom_f", $this->prenom_f);
        $query->bindParam(":mail_f", $this->mail_f);
        $query->bindParam(":cp_f", $this->cp_f);
        $query->bindParam(":ville_f", $this->ville_f);
        $query->bindParam(":adresse_f", $this->adresse_f);
        $query->bindParam(":service_f", $this->service_f);
        $query->bindParam(":copie_mail_f", $this->copie_mail_f);
        $query->bindParam(":mail_livraison", $this->mail_livraison);
        $query->bindParam(":ftp", $this->ftp);
        $query->bindParam(":ip", $this->ip);
        $query->bindParam(":login", $this->login);
        $query->bindParam(":mdp", $this->mdp);
        $query->bindParam(":telbur", $this->telbur);
        $query->bindParam(":telmob", $this->telmob);
        $query->bindParam(":code_client", $this->code_client);
        $query->bindParam(":flag", $this->flag);

        # Pour la modification
        $isUpdateQuery && $query->bindParam(":mailForContactToUpdateOrDelete", $this->mailForContactToUpdateOrDelete);

        if ($query->execute()) {
            return true;
        }

        return false;
    }
}