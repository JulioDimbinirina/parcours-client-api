<?php

namespace App\Entity;

use App\Repository\FicheClientRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=FicheClientRepository::class)
 */
class FicheClient
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"save-fiche", "list-fiche"})
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"save-fiche", "list-fiche"})
     */
    private $rc;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"save-fiche", "list-fiche"})
     */
    private $activiteContexte;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Groups({"save-fiche", "list-fiche"})
     */
    private $gestionnaireDeCompte;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"save-fiche", "list-fiche"})
     */
    private $rapportActivite;

    /**
     * @ORM\Column(type="string", length=25, nullable=true)
     * @Groups({"save-fiche", "list-fiche"})
     */
    private $statutContrat;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     * @Groups({"save-fiche", "list-fiche"})
     */
    private $taciteReconduction;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"save-fiche", "list-fiche"})
     */
    private $versementAcompte;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Groups({"save-fiche", "list-fiche"})
     */
    private $typeProfil;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Groups({"save-fiche", "list-fiche"})
     */
    private $niveau;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Groups({"save-fiche", "list-fiche"})
     */
    private $langueTrt;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Groups({"save-fiche", "list-fiche"})
     */
    private $formation;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"save-fiche", "list-fiche"})
     */
    private $dateDemarragePartenariat;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"save-fiche", "list-fiche"})
     */
    private $dimensionnement;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"save-fiche", "list-fiche"})
     */
    private $budgetDeMiseEnPlace;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Groups({"save-fiche", "list-fiche"})
     */
    private $budgetFormation;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Groups({"save-fiche", "list-fiche"})
     */
    private $budgetAnnuel;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Groups({"save-fiche", "list-fiche"})
     */
    private $budgetMoyenMensuel;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Groups({"save-fiche", "list-fiche"})
     */
    private $budgetM1;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Groups({"save-fiche", "list-fiche"})
     */
    private $moyenneQualite;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Groups({"save-fiche", "list-fiche"})
     */
    private $moyenneSatcli;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Groups({"save-fiche", "list-fiche"})
     */
    private $confSpecifique;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"save-fiche", "list-fiche"})
     */
    private $specificiteContractuelles;

    /**
     * @ORM\OneToOne(targetEntity=Customer::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"save-fiche"})
     */
    private $customer;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"save-fiche", "list-fiche"})
     */
    private $forfaitPilotage;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"save-fiche", "list-fiche"})
     */
    private $chiffreAffaireRealise;

    /**
     * @ORM\Column(type="string", length=80, nullable=true)
     * @Groups({"save-fiche", "list-fiche"})
     */
    private $caM1;

    /**
     * @ORM\ManyToOne(targetEntity=NaturePrestation::class, inversedBy="ficheClients")
     * @ORM\JoinColumn(nullable=true)
     * @Groups({"save-fiche", "list-fiche"})
     */
    private $naturePrestation;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Groups({"save-fiche", "list-fiche"})
     */
    private $dureeAnciennete;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Groups({"save-fiche", "list-fiche"})
     */
    private $registreTraitement;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Groups({"save-fiche", "list-fiche"})
     */
    private $annexeContrat;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"save-fiche", "list-fiche"})
     */
    private $outils;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"save-fiche", "list-fiche"})
     */
    private $commercial;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRc(): ?string
    {
        return $this->rc;
    }

    public function setRc(?string $rc): self
    {
        $this->rc = $rc;

        return $this;
    }

    public function getActiviteContexte(): ?string
    {
        return $this->activiteContexte;
    }

    public function setActiviteContexte(?string $activiteContexte): self
    {
        $this->activiteContexte = $activiteContexte;

        return $this;
    }

    public function getGestionnaireDeCompte(): ?string
    {
        return $this->gestionnaireDeCompte;
    }

    public function setGestionnaireDeCompte(?string $gestionnaireDeCompte): self
    {
        $this->gestionnaireDeCompte = $gestionnaireDeCompte;

        return $this;
    }

    public function getRapportActivite(): ?string
    {
        return $this->rapportActivite;
    }

    public function setRapportActivite(?string $rapportActivite): self
    {
        $this->rapportActivite = $rapportActivite;

        return $this;
    }

    public function getStatutContrat(): ?string
    {
        return $this->statutContrat;
    }

    public function setStatutContrat(?string $statutContrat): self
    {
        $this->statutContrat = $statutContrat;

        return $this;
    }

    public function getTaciteReconduction(): ?string
    {
        return $this->taciteReconduction;
    }

    public function setTaciteReconduction(?string $taciteReconduction): self
    {
        $this->taciteReconduction = $taciteReconduction;

        return $this;
    }

    public function getVersementAcompte(): ?string
    {
        return $this->versementAcompte;
    }

    public function setVersementAcompte(?string $versementAcompte): self
    {
        $this->versementAcompte = $versementAcompte;

        return $this;
    }

    public function getTypeProfil(): ?string
    {
        return $this->typeProfil;
    }

    public function setTypeProfil(?string $typeProfil): self
    {
        $this->typeProfil = $typeProfil;

        return $this;
    }

    public function getNiveau(): ?string
    {
        return $this->niveau;
    }

    public function setNiveau(?string $niveau): self
    {
        $this->niveau = $niveau;

        return $this;
    }

    public function getLangueTrt(): ?string
    {
        return $this->langueTrt;
    }

    public function setLangueTrt(?string $langueTrt): self
    {
        $this->langueTrt = $langueTrt;

        return $this;
    }

    public function getFormation(): ?string
    {
        return $this->formation;
    }

    public function setFormation(?string $formation): self
    {
        $this->formation = $formation;

        return $this;
    }

    public function getDateDemarragePartenariat(): ?\DateTimeInterface
    {
        return $this->dateDemarragePartenariat;
    }

    public function setDateDemarragePartenariat(?\DateTimeInterface $dateDemarragePartenariat): self
    {
        $this->dateDemarragePartenariat = $dateDemarragePartenariat;

        return $this;
    }

    public function getDimensionnement(): ?string
    {
        return $this->dimensionnement;
    }

    public function setDimensionnement(?string $dimensionnement): self
    {
        $this->dimensionnement = $dimensionnement;

        return $this;
    }

    public function getBudgetDeMiseEnPlace(): ?string
    {
        return $this->budgetDeMiseEnPlace;
    }

    public function setBudgetDeMiseEnPlace(?string $budgetDeMiseEnPlace): self
    {
        $this->budgetDeMiseEnPlace = $budgetDeMiseEnPlace;

        return $this;
    }

    public function getBudgetFormation(): ?string
    {
        return $this->budgetFormation;
    }

    public function setBudgetFormation(?string $budgetFormation): self
    {
        $this->budgetFormation = $budgetFormation;

        return $this;
    }

    public function getBudgetAnnuel(): ?string
    {
        return $this->budgetAnnuel;
    }

    public function setBudgetAnnuel(?string $budgetAnnuel): self
    {
        $this->budgetAnnuel = $budgetAnnuel;

        return $this;
    }

    public function getBudgetMoyenMensuel(): ?string
    {
        return $this->budgetMoyenMensuel;
    }

    public function setBudgetMoyenMensuel(?string $budgetMoyenMensuel): self
    {
        $this->budgetMoyenMensuel = $budgetMoyenMensuel;

        return $this;
    }

    public function getBudgetM1(): ?string
    {
        return $this->budgetM1;
    }

    public function setBudgetM1(?string $budgetM1): self
    {
        $this->budgetM1 = $budgetM1;

        return $this;
    }

    public function getMoyenneQualite(): ?string
    {
        return $this->moyenneQualite;
    }

    public function setMoyenneQualite(?string $moyenneQualite): self
    {
        $this->moyenneQualite = $moyenneQualite;

        return $this;
    }

    public function getMoyenneSatcli(): ?string
    {
        return $this->moyenneSatcli;
    }

    public function setMoyenneSatcli(?string $moyenneSatcli): self
    {
        $this->moyenneSatcli = $moyenneSatcli;

        return $this;
    }

    public function getConfSpecifique(): ?string
    {
        return $this->confSpecifique;
    }

    public function setConfSpecifique(?string $confSpecifique): self
    {
        $this->confSpecifique = $confSpecifique;

        return $this;
    }

    public function getSpecificiteContractuelles(): ?string
    {
        return $this->specificiteContractuelles;
    }

    public function setSpecificiteContractuelles(?string $specificiteContractuelles): self
    {
        $this->specificiteContractuelles = $specificiteContractuelles;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function getForfaitPilotage(): ?string
    {
        return $this->forfaitPilotage;
    }

    public function setForfaitPilotage(?string $forfaitPilotage): self
    {
        $this->forfaitPilotage = $forfaitPilotage;

        return $this;
    }

    public function getChiffreAffaireRealise(): ?string
    {
        return $this->chiffreAffaireRealise;
    }

    public function setChiffreAffaireRealise(?string $chiffreAffaireRealise): self
    {
        $this->chiffreAffaireRealise = $chiffreAffaireRealise;

        return $this;
    }

    public function getCaM1(): ?string
    {
        return $this->caM1;
    }

    public function setCaM1(?string $caM1): self
    {
        $this->caM1 = $caM1;

        return $this;
    }

    public function getNaturePrestation(): ?NaturePrestation
    {
        return $this->naturePrestation;
    }

    public function setNaturePrestation(?NaturePrestation $naturePrestation): self
    {
        $this->naturePrestation = $naturePrestation;

        return $this;
    }

    public function getDureeAnciennete(): ?string
    {
        return $this->dureeAnciennete;
    }

    public function setDureeAnciennete(?string $dureeAnciennete): self
    {
        $this->dureeAnciennete = $dureeAnciennete;

        return $this;
    }

    public function getRegistreTraitement(): ?string
    {
        return $this->registreTraitement;
    }

    public function setRegistreTraitement(?string $registreTraitement): self
    {
        $this->registreTraitement = $registreTraitement;

        return $this;
    }

    public function getAnnexeContrat(): ?string
    {
        return $this->annexeContrat;
    }

    public function setAnnexeContrat(?string $annexeContrat): self
    {
        $this->annexeContrat = $annexeContrat;

        return $this;
    }

    public function getOutils(): ?string
    {
        return $this->outils;
    }

    public function setOutils(?string $outils): self
    {
        $this->outils = $outils;

        return $this;
    }

    public function getCommercial(): ?string
    {
        return $this->commercial;
    }

    public function setCommercial(?string $commercial): self
    {
        $this->commercial = $commercial;

        return $this;
    }
}
