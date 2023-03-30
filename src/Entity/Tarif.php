<?php

namespace App\Entity;

use App\Repository\TarifRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TarifRepository::class)
 */
class Tarif
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateFin;

    /**
     * @ORM\ManyToOne(targetEntity=Bu::class, inversedBy="tarifs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $bu;

    /**
     * @ORM\ManyToOne(targetEntity=TypeFacturation::class, inversedBy="tarifs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $typeFacturation;

    /**
     * @ORM\ManyToOne(targetEntity=PaysProduction::class, inversedBy="tarifs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $paysProduction;

    /**
     * @ORM\ManyToOne(targetEntity=Operation::class, inversedBy="tarifs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $operation;

    /**
     * @ORM\ManyToOne(targetEntity=LangueTrt::class, inversedBy="tarifs")
     * @ORM\JoinColumn(nullable=false)
     */
    private $langueTraitement;

    /**
     * @ORM\OneToMany(targetEntity=BdcOperation::class, mappedBy="tarif")
     */
    private $bdcOperations;

    public function __construct()
    {
        $this->bdcOperations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(?\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(?\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

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

    public function getTypeFacturation(): ?TypeFacturation
    {
        return $this->typeFacturation;
    }

    public function setTypeFacturation(?TypeFacturation $typeFacturation): self
    {
        $this->typeFacturation = $typeFacturation;

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

    public function getOperation(): ?Operation
    {
        return $this->operation;
    }

    public function setOperation(?Operation $operation): self
    {
        $this->operation = $operation;

        return $this;
    }

    public function getLangueTraitement(): ?LangueTrt
    {
        return $this->langueTraitement;
    }

    public function setLangueTraitement(?LangueTrt $langueTraitement): self
    {
        $this->langueTraitement = $langueTraitement;

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
            $bdcOperation->setTarif($this);
        }

        return $this;
    }

    public function removeBdcOperation(BdcOperation $bdcOperation): self
    {
        if ($this->bdcOperations->removeElement($bdcOperation)) {
            // set the owning side to null (unless already changed)
            if ($bdcOperation->getTarif() === $this) {
                $bdcOperation->setTarif(null);
            }
        }

        return $this;
    }
}
