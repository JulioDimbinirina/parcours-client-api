<?php

namespace App\Entity;

use App\Repository\BdcOperationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=BdcOperationRepository::class)
 */
class BdcOperation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"get-by-bdc", "fiche-client", "get-ca", "bdc-operation", "via-irm", "post:read", "via-irm", "inject:cout", "saisie:manager"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"get-by-bdc", "update-bdc", "fiche-client", "get-ca", "bdc-operation"})
     */
    private $quantite;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     * @Groups({"get-by-bdc", "update", "update-bdc", "fiche-client", "get-ca", "bdc-operation"})
     */
    private $prixUnit;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     * @Groups({"get-by-bdc", "update-bdc", "bdc-operation","post:read"})
     */
    private $irm;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     * @Groups({"get-by-bdc", "update-bdc", "bdc-operation"})
     */
    private $siRenta;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     * @Groups({"get-by-bdc", "update-bdc", "bdc-operation"})
     */
    private $sage;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=0, nullable=true)
     * @Groups({"get-by-bdc", "update", "update-bdc", "fiche-client", "bdc-operation"})
     */
    private $tarifHoraireCible;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"get-by-bdc", "update-bdc", "bdc-operation"})
     */
    private $objectif;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     * @Groups({"get-by-bdc", "update", "update-bdc", "fiche-client", "bdc-operation"})
     */
    private $tempsProductifs;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Groups({"get-by-bdc", "update", "update-bdc", "fiche-client", "bdc-operation"})
     */
    private $dmt;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=0, nullable=true)
     * @Groups({"get-by-bdc", "update", "update-bdc", "bdc-operation"})
     */
    private $tarifHoraireFormation;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"get-by-bdc", "update-bdc", "bdc-operation"})
     */
    private $volumeATraite;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     * @Groups({"get-by-bdc", "update-bdc", "bdc-operation"})
     */
    private $categorieLead;

    /**
     * @ORM\ManyToOne(targetEntity=Bdc::class, inversedBy="bdcOperations", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"post:read", "update", "saisie:manager"})
     */
    private $bdc;

    /**
     * @ORM\ManyToOne(targetEntity=Operation::class, inversedBy="bdcOperations", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"get-by-bdc", "update-bdc", "fiche-client", "bdc-operation", "via-irm", "post:read", "inject:cout", "saisie:manager"})
     */
    private $operation;

    /**
     * @ORM\ManyToOne(targetEntity=LangueTrt::class, inversedBy="bdcOperations", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     *  @Groups({"get-by-bdc", "update-bdc", "bdc-operation"})
     */
    private $langueTrt;

    /**
     * @ORM\ManyToOne(targetEntity=TypeFacturation::class, inversedBy="bdcOperations", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     * @Groups({"get-by-bdc", "update-bdc", "bdc-operation", "post:read", "fiche-client"})
     */
    private $typeFacturation;

    /**
     * @ORM\ManyToOne(targetEntity=FamilleOperation::class, inversedBy="bdcOperations", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     * @Groups({"bdc-operation", "post:read", "get-by-bdc"})
     */
    private $familleOperation;

    /**
     * @ORM\ManyToOne(targetEntity=Bu::class, inversedBy="bdcOperations", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     * @Groups({"get-by-bdc", "update-bdc", "bdc-operation", "post:read", "fiche-client", "saisie:manager"})
     */
    private $bu;

    /**
     * @ORM\ManyToMany(targetEntity=ObjectifQualitatif::class, cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     * @Groups({"update-bdc", "fiche-client", "bdc-operation", "via-irm", "get-by-bdc"})
     */
    private $objectifQualitatif;

    /**
     * @ORM\ManyToMany(targetEntity=ObjectifQuantitatif::class, cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     * @Groups({"update-bdc", "fiche-client", "bdc-operation", "via-irm", "get-by-bdc"})
     */
    private $objectifQuantitatif;

    /**
     * @ORM\ManyToOne(targetEntity=CoutHoraire::class, inversedBy="bdcOperations", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     * @Groups({"update", "update-bdc", "get-by-bdc", "fiche-client", "bdc-operation", "inject:cout", "saisie:manager"})
     */
    private $coutHoraire;



    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"get-by-bdc", "update-bdc", "bdc-operation"})
     */
    private $prodParHeure;

    /**
     * @ORM\ManyToOne(targetEntity=Tarif::class, inversedBy="bdcOperations")
     * @ORM\JoinColumn(nullable=true)
     * @Groups({"bdc-operation"})
     */
    private $tarif;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"contact", "update", "update-bdc", "get-by-bdc", "via-irm", "saisie:manager"})
     */
    private $irmOperation;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $avenant;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"update", "update-bdc", "get-by-bdc"})
     */
    private $description;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"update", "update-bdc", "get-by-bdc"})
     */
    private $isHnoDimanche;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"update", "update-bdc", "get-by-bdc"})
     */
    private $isHnoHorsDimanche;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"update", "update-bdc", "get-by-bdc"})
     */
    private $majoriteHnoDimanche;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"update", "update-bdc", "get-by-bdc"})
     */
    private $majoriteHnoHorsDimanche;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     * @Groups({"update", "update-bdc", "get-by-bdc"})
     */
    private $valueHno;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"update", "update-bdc", "get-by-bdc"})
     */
    private $offert;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"update", "update-bdc", "get-by-bdc"})
     */
    private $Duree;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"update", "update-bdc", "get-by-bdc"})
     */
    private $ressourceFormer;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Groups({"update", "update-bdc", "get-by-bdc"})
     */
    private $nbHeureMensuel;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"update", "update-bdc", "get-by-bdc"})
     */
    private $nbEtp;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Groups({"update", "update-bdc", "get-by-bdc"})
     */
    private $uniqBdcFqOperation;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     * @Groups({"update", "update-bdc", "get-by-bdc"})
     */
    private $isParamPerformed;

    /**
     * @ORM\OneToMany(targetEntity=IndicatorQuantitatif::class, mappedBy="bdcOperation", cascade={"persist"})
     * @Groups({"update", "update-bdc", "get-by-bdc"})
     */
    private $indicatorQuantitatifs;

    /**
     * @ORM\OneToMany(targetEntity=IndicatorQualitatif::class, mappedBy="bdcOperation", cascade={"persist"})
     * @Groups({"update", "update-bdc", "get-by-bdc"})
     */
    private $indicatorQualitatifs;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     * @Groups({"get-by-bdc", "update", "update-bdc", "fiche-client", "get-ca", "bdc-operation"})
     */
    private $oldPrixUnit;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"update", "update-bdc", "get-by-bdc"})
     */
    private $encodedImage;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     * @Groups({"update", "update-bdc", "get-by-bdc"})
     */
    private $productiviteActe;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"update", "update-bdc", "get-by-bdc"})
     */
    private $quantiteActe;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"update", "update-bdc", "get-by-bdc"})
     */
    private $quantiteHeure;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     * @Groups({"update", "update-bdc", "get-by-bdc"})
     */
    private $prixUnitaireActe;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     * @Groups({"update", "update-bdc", "get-by-bdc"})
     */
    private $prixUnitaireHeure;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"update", "update-bdc", "get-by-bdc"})
     */
    private $applicatifDate;

    /**
     * @ORM\ManyToOne(targetEntity=Operation::class, inversedBy="bdcOperations", cascade={"persist"})
     * @Groups({"update", "update-bdc", "get-by-bdc"})
     */
    private $designationActe;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $oldPrixUnitHeure;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $oldPrixUnitActe;

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

    public function getQuantite(): ?string
    {
        return $this->quantite;
    }

    public function setQuantite(?string $quantite): self
    {
        $this->quantite = $quantite;

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

    public function getIrm(): ?int
    {
        return $this->irm;
    }

    public function setIrm(?int $irm): self
    {
        $this->irm = $irm;

        return $this;
    }

    public function getSiRenta(): ?int
    {
        return $this->siRenta;
    }

    public function setSiRenta(?int $siRenta): self
    {
        $this->siRenta = $siRenta;

        return $this;
    }

    public function getSage(): ?int
    {
        return $this->sage;
    }

    public function setSage(?int $sage): self
    {
        $this->sage = $sage;

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

    public function getObjectif(): ?string
    {
        return $this->objectif;
    }

    public function setObjectif(?string $objectif): self
    {
        $this->objectif = $objectif;

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

    public function getTarifHoraireFormation(): ?string
    {
        return $this->tarifHoraireFormation;
    }

    public function setTarifHoraireFormation(?string $tarifHoraireFormation): self
    {
        $this->tarifHoraireFormation = $tarifHoraireFormation;

        return $this;
    }

    public function getVolumeATraite(): ?int
    {
        return $this->volumeATraite;
    }

    public function setVolumeATraite(?int $volumeATraite): self
    {
        $this->volumeATraite = $volumeATraite;

        return $this;
    }

    public function getCategorieLead(): ?string
    {
        return $this->categorieLead;
    }

    public function setCategorieLead(?string $categorieLead): self
    {
        $this->categorieLead = $categorieLead;

        return $this;
    }

    public function getBdc(): ?Bdc
    {
        return $this->bdc;
    }

    public function setBdc(?Bdc $bdc): self
    {
        $this->bdc = $bdc;

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

    public function getLangueTrt(): ?LangueTrt
    {
        return $this->langueTrt;
    }

    public function setLangueTrt(?LangueTrt $langueTrt): self
    {
        $this->langueTrt = $langueTrt;

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

    public function getFamilleOperation(): ?FamilleOperation
    {
        return $this->familleOperation;
    }

    public function setFamilleOperation(?FamilleOperation $familleOperation): self
    {
        $this->familleOperation = $familleOperation;

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

    public function getIrmOperation(): ?string
    {
        return $this->irmOperation;
    }

    public function setIrmOperation(?string $irmOperation): self
    {
        $this->irmOperation = $irmOperation;
        return $this;
    }

    /**
     * @return Collection|ObjectifQualitatif[]
     */
    public function getObjectifQualitatif(): Collection
    {
        return $this->objectifQualitatif;
    }

    public function addObjectifQualitatif(?ObjectifQualitatif $objectifQualitatif): self
    {
        if (!$this->objectifQualitatif->contains($objectifQualitatif)) {
            $this->objectifQualitatif[] = $objectifQualitatif;
        }
        return $this;
    }

    public function removeObjectifQualitatif(?ObjectifQualitatif $objectifQualitatif): self
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

    public function addObjectifQuantitatif(?ObjectifQuantitatif $objectifQuantitatif): self
    {
        if (!$this->objectifQuantitatif->contains($objectifQuantitatif)) {
            $this->objectifQuantitatif[] = $objectifQuantitatif;
        }
        return $this;
    }

    public function removeObjectifQuantitatif(?ObjectifQuantitatif $objectifQuantitatif): self
    {
        $this->objectifQuantitatif->removeElement($objectifQuantitatif);
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

    public function getProdParHeure(): ?string
    {
        return $this->prodParHeure;
    }

    public function setProdParHeure(?string $prodParHeure): self
    {
        $this->prodParHeure = $prodParHeure;

        return $this;
    }

    public function getTarif(): ?Tarif
    {
        return $this->tarif;
    }

    public function setTarif(?Tarif $tarif): self
    {
        $this->tarif = $tarif;

        return $this;
    }

    public function getAvenant(): ?int
    {
        return $this->avenant;
    }

    public function setAvenant(?int $avenant): self
    {
        $this->avenant = $avenant;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getIsHnoDimanche(): ?int
    {
        return $this->isHnoDimanche;
    }

    public function setIsHnoDimanche(?int $isHnoDimanche): self
    {
        $this->isHnoDimanche = $isHnoDimanche;

        return $this;
    }

    public function getIsHnoHorsDimanche(): ?int
    {
        return $this->isHnoHorsDimanche;
    }

    public function setIsHnoHorsDimanche(?int $isHnoHorsDimanche): self
    {
        $this->isHnoHorsDimanche = $isHnoHorsDimanche;

        return $this;
    }

    public function getMajoriteHnoDimanche(): ?int
    {
        return $this->majoriteHnoDimanche;
    }

    public function setMajoriteHnoDimanche(?int $majoriteHnoDimanche): self
    {
        $this->majoriteHnoDimanche = $majoriteHnoDimanche;

        return $this;
    }

    public function getMajoriteHnoHorsDimanche(): ?int
    {
        return $this->majoriteHnoHorsDimanche;
    }

    public function setMajoriteHnoHorsDimanche(?int $majoriteHnoHorsDimanche): self
    {
        $this->majoriteHnoHorsDimanche = $majoriteHnoHorsDimanche;

        return $this;
    }

    public function getValueHno(): ?string
    {
        return $this->valueHno;
    }

    public function setValueHno(?string $valueHno): self
    {
        $this->valueHno = $valueHno;

        return $this;
    }

    public function getOffert(): ?int
    {
        return $this->offert;
    }

    public function setOffert(?int $offert): self
    {
        $this->offert = $offert;

        return $this;
    }

    public function getDuree(): ?string
    {
        return $this->Duree;
    }

    public function setDuree(?string $Duree): self
    {
        $this->Duree = $Duree;

        return $this;
    }

    public function getRessourceFormer(): ?string
    {
        return $this->ressourceFormer;
    }

    public function setRessourceFormer(?string $ressourceFormer): self
    {
        $this->ressourceFormer = $ressourceFormer;

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

    public function getUniqBdcFqOperation(): ?string
    {
        return $this->uniqBdcFqOperation;
    }

    public function setUniqBdcFqOperation(?string $uniqBdcFqOperation): self
    {
        $this->uniqBdcFqOperation = $uniqBdcFqOperation;

        return $this;
    }

    public function getIsParamPerformed(): ?int
    {
        return $this->isParamPerformed;
    }

    public function setIsParamPerformed(?int $isParamPerformed): self
    {
        $this->isParamPerformed = $isParamPerformed;

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
            $indicatorQuantitatif->setBdcOperation($this);
        }

        return $this;
    }

    public function removeIndicatorQuantitatif(IndicatorQuantitatif $indicatorQuantitatif): self
    {
        if ($this->indicatorQuantitatifs->removeElement($indicatorQuantitatif)) {
            // set the owning side to null (unless already changed)
            if ($indicatorQuantitatif->getBdcOperation() === $this) {
                $indicatorQuantitatif->setBdcOperation(null);
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
            $indicatorQualitatif->setBdcOperation($this);
        }

        return $this;
    }

    public function removeIndicatorQualitatif(IndicatorQualitatif $indicatorQualitatif): self
    {
        if ($this->indicatorQualitatifs->removeElement($indicatorQualitatif)) {
            // set the owning side to null (unless already changed)
            if ($indicatorQualitatif->getBdcOperation() === $this) {
                $indicatorQualitatif->setBdcOperation(null);
            }
        }

        return $this;
    }

    public function getOldPrixUnit(): ?string
    {
        return $this->oldPrixUnit;
    }

    public function setOldPrixUnit(?string $oldPrixUnit): self
    {
        $this->oldPrixUnit = $oldPrixUnit;

        return $this;
    }

    public function getEncodedImage(): ?string
    {
        return $this->encodedImage;
    }

    public function setEncodedImage(?string $encodedImage): self
    {
        $this->encodedImage = $encodedImage;

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

    public function getApplicatifDate(): ?\DateTimeInterface
    {
        return $this->applicatifDate;
    }

    public function setApplicatifDate(?\DateTimeInterface $applicatifDate): self
    {
        $this->applicatifDate = $applicatifDate;

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

    public function getOldPrixUnitHeure(): ?string
    {
        return $this->oldPrixUnitHeure;
    }

    public function setOldPrixUnitHeure(?string $oldPrixUnitHeure): self
    {
        $this->oldPrixUnitHeure = $oldPrixUnitHeure;

        return $this;
    }

    public function getOldPrixUnitActe(): ?string
    {
        return $this->oldPrixUnitActe;
    }

    public function setOldPrixUnitActe(?string $oldPrixUnitActe): self
    {
        $this->oldPrixUnitActe = $oldPrixUnitActe;

        return $this;
    }
}