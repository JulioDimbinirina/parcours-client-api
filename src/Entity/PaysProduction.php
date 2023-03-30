<?php

namespace App\Entity;

use App\Repository\PaysProductionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PaysProductionRepository::class)
 */
class PaysProduction
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"get-by-bdc", "ref", "bdcs", "get-fq-id", "fiche-client", "status:lead", "bdcs", "current-user", "inject:cout"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     * @Groups({"get-by-bdc","ref", "bdcs", "get-fq-id", "fiche-client", "status:lead", "inject:cout"})
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=LeadDetailOperation::class, mappedBy="paysProduction")
     */
    private $leadDetailOperations;

    /**
     * @ORM\OneToMany(targetEntity=Bdc::class, mappedBy="paysProduction")
     */
    private $bdcs;

    /**
     * @ORM\OneToMany(targetEntity=Tarif::class, mappedBy="paysProduction")
     */
    private $tarifs;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="paysProduction")
     */
    private $users;

    public function __construct()
    {
        $this->leadDetailOperations = new ArrayCollection();
        $this->bdcs = new ArrayCollection();
        $this->tarifs = new ArrayCollection();
        $this->users = new ArrayCollection();
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
     * @return Collection|Bdc[]
     */
    /* public function getBdcs(): Collection
    {
        return $this->bdcs;
    } */
/* 
    public function addBdc(Bdc $bdc): self
    {
        if (!$this->bdcs->contains($bdc)) {
            $this->bdcs[] = $bdc;
            $bdc->setPaysProduction($this);
        }

        return $this;
    } */

    /* public function removeBdc(Bdc $bdc): self
    {
        if ($this->bdcs->removeElement($bdc)) {
            // set the owning side to null (unless already changed)
            if ($bdc->getPaysProduction() === $this) {
                $bdc->setPaysProduction(null);
            }
        }

        return $this;
    } */

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
            $leadDetailOperation->setPaysProduction($this);
        }

        return $this;
    }

    public function removeLeadDetailOperation(LeadDetailOperation $leadDetailOperation): self
    {
        if ($this->leadDetailOperations->removeElement($leadDetailOperation)) {
            // set the owning side to null (unless already changed)
            if ($leadDetailOperation->getPaysProduction() === $this) {
                $leadDetailOperation->setPaysProduction(null);
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
            $bdc->setPaysProduction($this);
        }

        return $this;
    }

    public function removeBdc(Bdc $bdc): self
    {
        if ($this->bdcs->removeElement($bdc)) {
            // set the owning side to null (unless already changed)
            if ($bdc->getPaysProduction() === $this) {
                $bdc->setPaysProduction(null);
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
            $tarif->setPaysProduction($this);
        }

        return $this;
    }

    public function removeTarif(Tarif $tarif): self
    {
        if ($this->tarifs->removeElement($tarif)) {
            // set the owning side to null (unless already changed)
            if ($tarif->getPaysProduction() === $this) {
                $tarif->setPaysProduction(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setPaysProduction($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getPaysProduction() === $this) {
                $user->setPaysProduction(null);
            }
        }

        return $this;
    }
}
