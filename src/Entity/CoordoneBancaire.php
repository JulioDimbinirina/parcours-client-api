<?php

namespace App\Entity;

use App\Repository\CoordoneBancaireRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CoordoneBancaireRepository::class)
 */
class CoordoneBancaire
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titulaire;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $domiciliation;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $rib;

    /**
     * @ORM\ManyToOne(targetEntity=SocieteFacturation::class, inversedBy="coordoneBancaires")
     * @ORM\JoinColumn(nullable=false)
     */
    private $societeFacturation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitulaire(): ?string
    {
        return $this->titulaire;
    }

    public function setTitulaire(string $titulaire): self
    {
        $this->titulaire = $titulaire;

        return $this;
    }

    public function getDomiciliation(): ?string
    {
        return $this->domiciliation;
    }

    public function setDomiciliation(string $domiciliation): self
    {
        $this->domiciliation = $domiciliation;

        return $this;
    }

    public function getRib(): ?string
    {
        return $this->rib;
    }

    public function setRib(string $rib): self
    {
        $this->rib = $rib;

        return $this;
    }

    public function getSocieteFacturation(): ?SocieteFacturation
    {
        return $this->societeFacturation;
    }

    public function setSocieteFacturation(?SocieteFacturation $societeFacturation): self
    {
        $this->societeFacturation = $societeFacturation;

        return $this;
    }
}
