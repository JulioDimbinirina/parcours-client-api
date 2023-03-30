<?php

namespace App\Entity;

use App\Repository\CoutHoraireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CoutHoraireRepository::class)
 */
class CoutHoraire
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"cout-horaire", "get-by-bdc", "update", "fiche-client", "profil-agent", "bdc-operation", "post:read", "get-fq-id", "inject:cout", "saisie:manager"})
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     * @Groups({"cout-horaire", "profil-agent", "get-fq-id", "inject:cout"})
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="date")
     * @Groups({"cout-horaire", "profil-agent", "get-fq-id", "inject:cout"})
     */
    private $dateFin;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"cout-horaire", "get-by-bdc", "profil-agent", "bdc-operation", "post:read", "get-fq-id", "inject:cout", "saisie:manager"})
     */
    private $pays;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"cout-horaire", "get-by-bdc", "profil-agent", "bdc-operation", "get-fq-id"})
     */
    private $niveau;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"cout-horaire", "profil-agent", "post:read", "get-fq-id"})
     */
    private $langueSpecialite;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * @Groups({"cout-horaire", "fiche-client", "profil-agent", "bdc-operation", "get-fq-id", "inject:cout"})
     */
    private $coutHoraire;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     * @Groups({"cout-horaire", "profil-agent", "inject:cout"})
     */
    private $coutFormation;

    /**
     * @ORM\OneToMany(targetEntity=BdcOperation::class, mappedBy="coutHoraire", orphanRemoval=true)
     */
    private $bdcOperations;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"cout-horaire", "get-by-bdc", "profil-agent", "bdc-operation", "post:read", "get-fq-id", "inject:cout"})
     */
    private $bu;

    /**
     * @ORM\OneToMany(targetEntity=LeadDetailOperation::class, mappedBy="coutHoraire")
     */
    private $leadDetailOperations;

    public function __construct()
    {
        $this->bdcOperations = new ArrayCollection();
        $this->leadDetailOperations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(?string $pays): self
    {
        $this->pays = $pays;

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

    public function getLangueSpecialite(): ?string
    {
        return $this->langueSpecialite;
    }

    public function setLangueSpecialite(?string $langueSpecialite): self
    {
        $this->langueSpecialite = $langueSpecialite;

        return $this;
    }

    public function getCoutHoraire(): ?string
    {
        return $this->coutHoraire;
    }

    public function setCoutHoraire(string $coutHoraire): self
    {
        $this->coutHoraire = $coutHoraire;

        return $this;
    }

    public function getCoutFormation(): ?string
    {
        return $this->coutFormation;
    }

    public function setCoutFormation(string $coutFormation): self
    {
        $this->coutFormation = $coutFormation;

        return $this;
    }

    /**
     * @return Collection|BdcOperation[]
     */
    public function getBdcOperations(): Collection
    {
        return $this->bdcOperations;
    }

    public function addBdcOperation(BdcOperation $bdcOperation): self
    {
        if (!$this->bdcOperations->contains($bdcOperation)) {
            $this->bdcOperations[] = $bdcOperation;
            $bdcOperation->setCoutHoraire($this);
        }

        return $this;
    }

    public function removeBdcOperation(BdcOperation $bdcOperation): self
    {
        if ($this->bdcOperations->removeElement($bdcOperation)) {
            // set the owning side to null (unless already changed)
            if ($bdcOperation->getCoutHoraire() === $this) {
                $bdcOperation->setCoutHoraire(null);
            }
        }

        return $this;
    }

    public function getBu(): ?string
    {
        return $this->bu;
    }

    public function setBu(?string $bu): self
    {
        $this->bu = $bu;

        return $this;
    }

    public function __toString() {
        return '';
    }

    /**
     * @return Collection|LeadDetailOperation[]
     */
    public function getLeadDetailOperations(): Collection
    {
        return $this->leadDetailOperations;
    }

    public function addLeadDetailOperation(LeadDetailOperation $leadDetailOperation): self
    {
        if (!$this->leadDetailOperations->contains($leadDetailOperation)) {
            $this->leadDetailOperations[] = $leadDetailOperation;
            $leadDetailOperation->setCoutHoraire($this);
        }

        return $this;
    }

    public function removeLeadDetailOperation(LeadDetailOperation $leadDetailOperation): self
    {
        if ($this->leadDetailOperations->removeElement($leadDetailOperation)) {
            // set the owning side to null (unless already changed)
            if ($leadDetailOperation->getCoutHoraire() === $this) {
                $leadDetailOperation->setCoutHoraire(null);
            }
        }

        return $this;
    }

}
