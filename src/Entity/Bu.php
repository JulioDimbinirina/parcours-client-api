<?php

namespace App\Entity;

use App\Repository\BuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=BuRepository::class)
 */
class Bu
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"get-by-bdc", "update-bdc", "get-fq-id", "ref", "bdc-operation", "post:read", "fiche-client", "saisie:manager"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"get-by-bdc", "update-bdc", "get-fq-id", "ref", "bdc-operation", "post:read", "fiche-client", "saisie:manager"})
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=LeadDetailOperation::class, mappedBy="bu", orphanRemoval=true)
     */
    private $leadDetailOperations;

    /**
     * @ORM\OneToMany(targetEntity=Tarif::class, mappedBy="bu", orphanRemoval=true)
     */
    private $tarifs;

    /**
     * @ORM\OneToMany(targetEntity=BdcOperation::class, mappedBy="bu")
     */
    private $bdcOperations;

    public function __construct()
    {
        $this->leadDetailOperations = new ArrayCollection();
        $this->tarifs = new ArrayCollection();
        $this->bdcOperations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
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
            $leadDetailOperation->setBu($this);
        }

        return $this;
    }

    public function removeLeadDetailOperation(LeadDetailOperation $leadDetailOperation): self
    {
        if ($this->leadDetailOperations->removeElement($leadDetailOperation)) {
            // set the owning side to null (unless already changed)
            if ($leadDetailOperation->getBu() === $this) {
                $leadDetailOperation->setBu(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Tarif[]
     */
    public function getTarifs(): Collection
    {
        return $this->tarifs;
    }

    public function addTarif(Tarif $tarif): self
    {
        if (!$this->tarifs->contains($tarif)) {
            $this->tarifs[] = $tarif;
            $tarif->setBu($this);
        }

        return $this;
    }

    public function removeTarif(Tarif $tarif): self
    {
        if ($this->tarifs->removeElement($tarif)) {
            // set the owning side to null (unless already changed)
            if ($tarif->getBu() === $this) {
                $tarif->setBu(null);
            }
        }

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
            $bdcOperation->setBu($this);
        }

        return $this;
    }

    public function removeBdcOperation(BdcOperation $bdcOperation): self
    {
        if ($this->bdcOperations->removeElement($bdcOperation)) {
            // set the owning side to null (unless already changed)
            if ($bdcOperation->getBu() === $this) {
                $bdcOperation->setBu(null);
            }
        }

        return $this;
    }
}
