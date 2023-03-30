<?php

namespace App\Entity;

use App\Repository\PaysFacturationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PaysFacturationRepository::class)
 */
class PaysFacturation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"ref", "get-by-bdc", "get-fq-id"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     * @Groups({"ref", "get-by-bdc", "get-fq-id"})
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=SocieteFacturation::class, mappedBy="paysFacturation", orphanRemoval=true)
     * @Groups({"get-by-bdc", "ref"})
     */
    private $societeFacturations;

    /**
     * @ORM\OneToMany(targetEntity=Devise::class, mappedBy="paysFacturation", orphanRemoval=true)
     * @Groups({"get-by-bdc", "ref"})
     */
    private $devises;

    /**
     * @ORM\OneToMany(targetEntity=Tva::class, mappedBy="paysFacturation", orphanRemoval=true)
     * @Groups({"get-by-bdc"})
     */
    private $tvas;

    /**
     * @ORM\OneToMany(targetEntity=LeadDetailOperation::class, mappedBy="paysFacturation")
     */
    private $leadDetailOperations;

    /**
     * @ORM\OneToMany(targetEntity=Bdc::class, mappedBy="paysFacturation")
     */
    private $bdcs;

    public function __construct()
    {
        $this->societeFacturations = new ArrayCollection();
        $this->devises = new ArrayCollection();
        $this->tvas = new ArrayCollection();
        $this->bdcs = new ArrayCollection();
       $this->leadDetailOperations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection|SocieteFacturation[]
     */
    public function getSocieteFacturations(): Collection
    {
        return $this->societeFacturations;
    }

    public function addSocieteFacturation(SocieteFacturation $societeFacturation): self
    {
        if (!$this->societeFacturations->contains($societeFacturation)) {
            $this->societeFacturations[] = $societeFacturation;
            $societeFacturation->setPaysFacturation($this);
        }

        return $this;
    }

    public function removeSocieteFacturation(SocieteFacturation $societeFacturation): self
    {
        if ($this->societeFacturations->removeElement($societeFacturation)) {
            // set the owning side to null (unless already changed)
            if ($societeFacturation->getPaysFacturation() === $this) {
                $societeFacturation->setPaysFacturation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Devise[]
     */
    public function getDevises(): Collection
    {
        return $this->devises;
    }

    public function addDevise(Devise $devise): self
    {
        if (!$this->devises->contains($devise)) {
            $this->devises[] = $devise;
            $devise->setPaysFacturation($this);
        }

        return $this;
    }

    public function removeDevise(Devise $devise): self
    {
        if ($this->devises->removeElement($devise)) {
            // set the owning side to null (unless already changed)
            if ($devise->getPaysFacturation() === $this) {
                $devise->setPaysFacturation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Tva[]
     */
    public function getTvas(): Collection
    {
        return $this->tvas;
    }

    public function addTva(Tva $tva): self
    {
        if (!$this->tvas->contains($tva)) {
            $this->tvas[] = $tva;
            $tva->setPaysFacturation($this);
        }

        return $this;
    }

    public function removeTva(Tva $tva): self
    {
        if ($this->tvas->removeElement($tva)) {
            // set the owning side to null (unless already changed)
            if ($tva->getPaysFacturation() === $this) {
                $tva->setPaysFacturation(null);
            }
        }

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
            $leadDetailOperation->setPaysFacturation($this);
        }

        return $this;
    }

    public function removeLeadDetailOperation(LeadDetailOperation $leadDetailOperation): self
    {
        if ($this->leadDetailOperations->removeElement($leadDetailOperation)) {
            // set the owning side to null (unless already changed)
            if ($leadDetailOperation->getPaysFacturation() === $this) {
                $leadDetailOperation->setPaysFacturation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Bdc[]
     */
    public function getBdcs(): Collection
    {
        return $this->bdcs;
    }

    public function addBdc(Bdc $bdc): self
    {
        if (!$this->bdcs->contains($bdc)) {
            $this->bdcs[] = $bdc;
            $bdc->setPaysFacturation($this);
        }

        return $this;
    }

    public function removeBdc(Bdc $bdc): self
    {
        if ($this->bdcs->removeElement($bdc)) {
            // set the owning side to null (unless already changed)
            if ($bdc->getPaysFacturation() === $this) {
                $bdc->setPaysFacturation(null);
            }
        }

        return $this;
    }
}
