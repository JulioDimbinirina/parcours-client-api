<?php

namespace App\Entity;

use App\Repository\LeadDetailOperationRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=LeadDetailOperationRepository::class)
 */
class LeadDetailOperation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"get-fq-id", "fiche-client", "post:read", "update"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"get-fq-id", "post:read"})
     */
    private $categorieLead;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"get-fq-id", "post:read"})
     */
    private $dateDebutCross;

    /**
     * @ORM\ManyToOne(targetEntity=TypeFacturation::class, inversedBy="leadDetailOperations", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"get-fq-id", "fiche-client", "post:read"})
     */
    private $typeFacturation;

    /**
     * @ORM\ManyToOne(targetEntity=LangueTrt::class, inversedBy="leadDetailOperations", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"get-fq-id", "fiche-client", "post:read"})
     */
    private $langueTrt;

    /**
     * @ORM\ManyToOne(targetEntity=Bu::class, inversedBy="leadDetailOperations", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"get-fq-id", "fiche-client", "post:read"})
     */
    private $bu;

    /**
     * @ORM\ManyToOne(targetEntity=Operation::class, inversedBy="leadDetailOperations", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"get-fq-id", "fiche-client", "post:read"})
     */
    private $operation;

    /**
     * @ORM\ManyToOne(targetEntity=FamilleOperation::class, inversedBy="leadDetailOperations", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"get-fq-id", "post:read"})
     */
    private $familleOperation;

    /**
     * @ORM\ManyToOne(targetEntity=HoraireProduction::class, inversedBy="leadDetailOperations", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     * @Groups({"get-fq-id", "fiche-client", "post:read"})
     */
    private $horaireProduction;

    /**
     * @ORM\ManyToOne(targetEntity=ResumeLead::class, inversedBy="leadDetailOperations", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $resumeLead;

    /**
     * @ORM\ManyToOne(targetEntity=PaysFacturation::class, inversedBy="leadDetailOperations", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"get-fq-id"})
     */
    private $paysFacturation;

    /**
     * @ORM\ManyToOne(targetEntity=PaysProduction::class, inversedBy="leadDetailOperations", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"get-fq-id", "fiche-client"})
     */
    private $paysProduction;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Groups({"get-fq-id"})
     */
    private $heureJourOuvrable;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Groups({"get-fq-id"})
     */
    private $heureWeekEnd;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $hno;

    /**
     * @ORM\ManyToMany(targetEntity=ObjectifQualitatif::class, cascade={"persist"})
     * @Groups({"get-fq-id", "fiche-client", "post:read"})
     */
    private $objectifQualitatif;

    /**
     * @ORM\ManyToMany(targetEntity=ObjectifQuantitatif::class, cascade={"persist"})
     * @Groups({"get-fq-id", "fiche-client", "post:read"})
     */
    private $objectifQuantitatif;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     * @Groups({"get-fq-id", "fiche-client", "post:read"})
     */
    private $tarifHoraireCible;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     * @Groups({"get-fq-id", "fiche-client", "post:read"})
     */
    private $tempsProductifs;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     * @Groups({"get-fq-id", "fiche-client", "post:read"})
     */
    private $dmt;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     * @Groups({"get-fq-id", "fiche-client", "post:read"})
     */
    private $prixUnit;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Groups({"get-fq-id", "fiche-client", "post:read"})
     */
    private $nbHeureMensuel;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"get-fq-id", "fiche-client", "post:read"})
     */
    private $nbEtp;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Groups({"get-fq-id", "fiche-client", "post:read"})
     */
    private $volumeATraite;

    /**
     * @ORM\ManyToOne(targetEntity=CoutHoraire::class, inversedBy="leadDetailOperations", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     * @Groups({"get-fq-id", "fiche-client", "post:read"})
     */
    private $coutHoraire;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Groups({"get-fq-id", "fiche-client", "post:read"})
     */
    private $uniqBdcFqOperation;

    /**
     * @ORM\OneToMany(targetEntity=IndicatorQuantitatif::class, mappedBy="leadDetailOperation", cascade={"persist"})
     */
    private $indicatorQuantitatifs;

    /**
     * @ORM\OneToMany(targetEntity=IndicatorQualitatif::class, mappedBy="leadDetailOperation", cascade={"persist"})
     */
    private $indicatorQualitatifs;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     * @Groups({"get-fq-id", "fiche-client", "post:read"})
     */
    private $productiviteActe;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"get-fq-id", "fiche-client", "post:read"})
     */
    private $quantiteActe;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"get-fq-id", "fiche-client", "post:read"})
     */
    private $quantiteHeure;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     * @Groups({"get-fq-id", "fiche-client", "post:read"})
     */
    private $prixUnitaireActe;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     * @Groups({"get-fq-id", "fiche-client", "post:read"})
     */
    private $prixUnitaireHeure;

    /**
     * @ORM\ManyToOne(targetEntity=Operation::class, inversedBy="leadDetailOperations", cascade={"persist"})
     * @Groups({"get-fq-id", "fiche-client"})
     */
    private $designationActe;

    public function __construct()
    {
        $this->objectifQualitatif = new ArrayCollection();
        $this->objectifQuantitatif = new ArrayCollection();
        $this->indicatorQuantitatifs = new ArrayCollection();
        $this->indicatorQualitatifs = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategorieLead(): ?string
    {
        return $this->categorieLead;
    }

    public function setCategorieLead(string $categorieLead): self
    {
        $this->categorieLead = $categorieLead;

        return $this;
    }

    public function getDateDebutCross(): ?DateTimeInterface
    {
        return $this->dateDebutCross;
    }

    public function setDateDebutCross(?DateTimeInterface $dateDebutCross): self
    {
        $this->dateDebutCross = $dateDebutCross;

        return $this;
    }

    public function getResumeLead(): ?ResumeLead
    {
        return $this->resumeLead;
    }

    public function setResumeLead(?ResumeLead $resumeLead): self
    {
        $this->resumeLead = $resumeLead;

        return $this;
    }

    public function getTypeFacturation(): ?TypeFacturation
    {
        return $this->typeFacturation;
    }

    public function setTypeFacturation(?TypeFacturation $typeFacturation): self
    {
        $this->typeFacturation = $typeFacturation;

        return $this;
    }

    public function getLangueTrt(): ?LangueTrt
    {
        return $this->langueTrt;
    }

    public function setLangueTrt(?LangueTrt $langueTrt): self
    {
        $this->langueTrt = $langueTrt;

        return $this;
    }

    public function getBu(): ?Bu
    {
        return $this->bu;
    }

    public function setBu(?Bu $bu): self
    {
        $this->bu = $bu;

        return $this;
    }

    public function getOperation(): ?Operation
    {
        return $this->operation;
    }

    public function setOperation(?Operation $operation): self
    {
        $this->operation = $operation;

        return $this;
    }

    public function getFamilleOperation(): ?FamilleOperation
    {
        return $this->familleOperation;
    }

    public function setFamilleOperation(?FamilleOperation $familleOperation): self
    {
        $this->familleOperation = $familleOperation;

        return $this;
    }

    public function getHoraireProduction(): ?HoraireProduction
    {
        return $this->horaireProduction;
    }

    public function setHoraireProduction(?HoraireProduction $horaireProduction): self
    {
        $this->horaireProduction = $horaireProduction;

        return $this;
    }

    public function getPaysFacturation(): ?PaysFacturation
    {
        return $this->paysFacturation;
    }

    public function setPaysFacturation(?PaysFacturation $paysFacturation): self
    {
        $this->paysFacturation = $paysFacturation;

        return $this;
    }

    public function getPaysProduction(): ?PaysProduction
    {
        return $this->paysProduction;
    }

    public function setPaysProduction(?PaysProduction $paysProduction): self
    {
        $this->paysProduction = $paysProduction;

        return $this;
    }

    public function getHeureJourOuvrable(): ?string
    {
        return $this->heureJourOuvrable;
    }

    public function setHeureJourOuvrable(?string $heureJourOuvrable): self
    {
        $this->heureJourOuvrable = $heureJourOuvrable;

        return $this;
    }

    public function getHeureWeekEnd(): ?string
    {
        return $this->heureWeekEnd;
    }

    public function setHeureWeekEnd(?string $heureWeekEnd): self
    {
        $this->heureWeekEnd = $heureWeekEnd;

        return $this;
    }

    public function getHno(): ?string
    {
        return $this->hno;
    }

    public function setHno(?string $hno): self
    {
        $this->hno = $hno;

        return $this;
    }

    /**
     * @return Collection|ObjectifQualitatif[]
     */
    public function getObjectifQualitatif(): Collection
    {
        return $this->objectifQualitatif;
    }

    public function addObjectifQualitatif(ObjectifQualitatif $objectifQualitatif): self
    {
        if (!$this->objectifQualitatif->contains($objectifQualitatif)) {
            $this->objectifQualitatif[] = $objectifQualitatif;
        }

        return $this;
    }

    public function removeObjectifQualitatif(ObjectifQualitatif $objectifQualitatif): self
    {
        $this->objectifQualitatif->removeElement($objectifQualitatif);

        return $this;
    }

    /**
     * @return Collection|ObjectifQuantitatif[]
     */
    public function getObjectifQuantitatif(): Collection
    {
        return $this->objectifQuantitatif;
    }

    public function addObjectifQuantitatif(ObjectifQuantitatif $objectifQuantitatif): self
    {
        if (!$this->objectifQuantitatif->contains($objectifQuantitatif)) {
            $this->objectifQuantitatif[] = $objectifQuantitatif;
        }

        return $this;
    }

    public function removeObjectifQuantitatif(ObjectifQuantitatif $objectifQuantitatif): self
    {
        $this->objectifQuantitatif->removeElement($objectifQuantitatif);

        return $this;
    }

    public function getTarifHoraireCible(): ?string
    {
        return $this->tarifHoraireCible;
    }

    public function setTarifHoraireCible(?string $tarifHoraireCible): self
    {
        $this->tarifHoraireCible = $tarifHoraireCible;

        return $this;
    }

    public function getTempsProductifs(): ?string
    {
        return $this->tempsProductifs;
    }

    public function setTempsProductifs(?string $tempsProductifs): self
    {
        $this->tempsProductifs = $tempsProductifs;

        return $this;
    }

    public function getDmt(): ?string
    {
        return $this->dmt;
    }

    public function setDmt(?string $dmt): self
    {
        $this->dmt = $dmt;

        return $this;
    }

    public function getPrixUnit(): ?string
    {
        return $this->prixUnit;
    }

    public function setPrixUnit(?string $prixUnit): self
    {
        $this->prixUnit = $prixUnit;

        return $this;
    }

    public function getVolumeATraite(): ?string
    {
        return $this->volumeATraite;
    }

    public function setVolumeATraite(?string $volumeATraite): self
    {
        $this->volumeATraite = $volumeATraite;

        return $this;
    }

    public function getNbHeureMensuel(): ?string
    {
        return $this->nbHeureMensuel;
    }

    public function setNbHeureMensuel(?string $nbHeureMensuel): self
    {
        $this->nbHeureMensuel = $nbHeureMensuel;

        return $this;
    }

    public function getNbEtp(): ?string
    {
        return $this->nbEtp;
    }

    public function setNbEtp(?string $nbEtp): self
    {
        $this->nbEtp = $nbEtp;

        return $this;
    }

    public function getCoutHoraire(): ?CoutHoraire
    {
        return $this->coutHoraire;
    }

    public function setCoutHoraire(?CoutHoraire $coutHoraire): self
    {
        $this->coutHoraire = $coutHoraire;

        return $this;
    }

    public function getUniqBdcFqOperation(): ?string
    {
        return $this->uniqBdcFqOperation;
    }

    public function setUniqBdcFqOperation(?string $uniqBdcFqOperation): self
    {
        $this->uniqBdcFqOperation = $uniqBdcFqOperation;

        return $this;
    }

    /**
     * @return Collection|IndicatorQuantitatif[]
     */
    public function getIndicatorQuantitatifs(): Collection
    {
        return $this->indicatorQuantitatifs;
    }

    public function addIndicatorQuantitatif(IndicatorQuantitatif $indicatorQuantitatif): self
    {
        if (!$this->indicatorQuantitatifs->contains($indicatorQuantitatif)) {
            $this->indicatorQuantitatifs[] = $indicatorQuantitatif;
            $indicatorQuantitatif->setLeadDetailOperation($this);
        }

        return $this;
    }

    public function removeIndicatorQuantitatif(IndicatorQuantitatif $indicatorQuantitatif): self
    {
        if ($this->indicatorQuantitatifs->removeElement($indicatorQuantitatif)) {
            // set the owning side to null (unless already changed)
            if ($indicatorQuantitatif->getLeadDetailOperation() === $this) {
                $indicatorQuantitatif->setLeadDetailOperation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|IndicatorQualitatif[]
     */
    public function getIndicatorQualitatifs(): Collection
    {
        return $this->indicatorQualitatifs;
    }

    public function addIndicatorQualitatif(IndicatorQualitatif $indicatorQualitatif): self
    {
        if (!$this->indicatorQualitatifs->contains($indicatorQualitatif)) {
            $this->indicatorQualitatifs[] = $indicatorQualitatif;
            $indicatorQualitatif->setLeadDetailOperation($this);
        }

        return $this;
    }

    public function removeIndicatorQualitatif(IndicatorQualitatif $indicatorQualitatif): self
    {
        if ($this->indicatorQualitatifs->removeElement($indicatorQualitatif)) {
            // set the owning side to null (unless already changed)
            if ($indicatorQualitatif->getLeadDetailOperation() === $this) {
                $indicatorQualitatif->setLeadDetailOperation(null);
            }
        }

        return $this;
    }

    public function getProductiviteActe(): ?string
    {
        return $this->productiviteActe;
    }

    public function setProductiviteActe(?string $productiviteActe): self
    {
        $this->productiviteActe = $productiviteActe;

        return $this;
    }

    public function getQuantiteActe(): ?string
    {
        return $this->quantiteActe;
    }

    public function setQuantiteActe(?string $quantiteActe): self
    {
        $this->quantiteActe = $quantiteActe;

        return $this;
    }

    public function getQuantiteHeure(): ?string
    {
        return $this->quantiteHeure;
    }

    public function setQuantiteHeure(?string $quantiteHeure): self
    {
        $this->quantiteHeure = $quantiteHeure;

        return $this;
    }

    public function getPrixUnitaireActe(): ?string
    {
        return $this->prixUnitaireActe;
    }

    public function setPrixUnitaireActe(?string $prixUnitaireActe): self
    {
        $this->prixUnitaireActe = $prixUnitaireActe;

        return $this;
    }

    public function getPrixUnitaireHeure(): ?string
    {
        return $this->prixUnitaireHeure;
    }

    public function setPrixUnitaireHeure(?string $prixUnitaireHeure): self
    {
        $this->prixUnitaireHeure = $prixUnitaireHeure;

        return $this;
    }

    public function getDesignationActe(): ?Operation
    {
        return $this->designationActe;
    }

    public function setDesignationActe(?Operation $designationActe): self
    {
        $this->designationActe = $designationActe;

        return $this;
    }
}
