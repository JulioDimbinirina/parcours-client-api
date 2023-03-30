<?php

namespace App\Entity;

use App\Repository\NaturePrestationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=NaturePrestationRepository::class)
 */
class NaturePrestation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"ref", "list-fiche"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"ref", "list-fiche"})
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=FicheClient::class, mappedBy="naturePrestation")
     */
    private $ficheClients;

    public function __construct()
    {
        $this->ficheClients = new ArrayCollection();
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
     * @return Collection|FicheClient[]
     */
    public function getFicheClients(): Collection
    {
        return $this->ficheClients;
    }

    public function addFicheClient(FicheClient $ficheClient): self
    {
        if (!$this->ficheClients->contains($ficheClient)) {
            $this->ficheClients[] = $ficheClient;
            $ficheClient->setNaturePrestation($this);
        }

        return $this;
    }

    public function removeFicheClient(FicheClient $ficheClient): self
    {
        if ($this->ficheClients->removeElement($ficheClient)) {
            // set the owning side to null (unless already changed)
            if ($ficheClient->getNaturePrestation() === $this) {
                $ficheClient->setNaturePrestation(null);
            }
        }

        return $this;
    }
}
