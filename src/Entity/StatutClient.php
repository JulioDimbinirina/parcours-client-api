<?php

namespace App\Entity;

use App\Repository\StatutClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=StatutClientRepository::class)
 */
class StatutClient
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"get-by-bdc"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"get-by-bdc"})
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=Bdc::class, mappedBy="statutClient")
     */
    private $bdcs;

    public function __construct()
    {
        $this->bdcs = new ArrayCollection();
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
    public function getBdcs(): Collection
    {
        return $this->bdcs;
    }

    public function addBdc(Bdc $bdc): self
    {
        if (!$this->bdcs->contains($bdc)) {
            $this->bdcs[] = $bdc;
            $bdc->setStatutClient($this);
        }

        return $this;
    }

    public function removeBdc(Bdc $bdc): self
    {
        if ($this->bdcs->removeElement($bdc)) {
            // set the owning side to null (unless already changed)
            if ($bdc->getStatutClient() === $this) {
                $bdc->setStatutClient(null);
            }
        }

        return $this;
    }
}
