<?php

namespace App\Entity;

use App\Repository\HoraireProductionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=HoraireProductionRepository::class)
 */
class HoraireProduction
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("post:read")
     * @Groups({"get-fq-id", "fiche-client"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("post:read")
     * @Groups({"get-fq-id", "fiche-client"})
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=LeadDetailOperation::class, mappedBy="horaireProduction", orphanRemoval=true)
     */
    private $leadDetailOperations;

    public function __construct()
    {
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
            $leadDetailOperation->setHoraireProduction($this);
        }

        return $this;
    }

    public function removeLeadDetailOperation(LeadDetailOperation $leadDetailOperation): self
    {
        if ($this->leadDetailOperations->removeElement($leadDetailOperation)) {
            // set the owning side to null (unless already changed)
            if ($leadDetailOperation->getHoraireProduction() === $this) {
                $leadDetailOperation->setHoraireProduction(null);
            }
        }

        return $this;
    }
}
