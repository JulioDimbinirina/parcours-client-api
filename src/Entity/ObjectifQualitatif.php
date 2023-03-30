<?php

namespace App\Entity;

use App\Repository\ObjectifQualitatifRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ObjectifQualitatifRepository::class)
 */
class ObjectifQualitatif
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"ref", "get-by-bdc", "fiche-client", "bdc-operation", "get-fq-id"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     * @Groups({"ref", "get-by-bdc", "fiche-client", "bdc-operation", "via-irm", "get-fq-id"})
     */
    private $libelle;

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
     * @return Collection|BdcOperation[]
     */
   /*  public function getBdcOperations(): Collection
    {
        return $this->bdcOperations;
    }

    public function addBdcOperation(BdcOperation $bdcOperation): self
    {
        if (!$this->bdcOperations->contains($bdcOperation)) {
            $this->bdcOperations[] = $bdcOperation;
            $bdcOperation->setObjectifQualitatif($this);
        }

        return $this;
    }

    public function removeBdcOperation(BdcOperation $bdcOperation): self
    {
        if ($this->bdcOperations->removeElement($bdcOperation)) {
            // set the owning side to null (unless already changed)
            if ($bdcOperation->getObjectifQualitatif() === $this) {
                $bdcOperation->setObjectifQualitatif(null);
            }
        }

        return $this;
    } */
}
