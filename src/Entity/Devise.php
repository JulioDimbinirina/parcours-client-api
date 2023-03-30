<?php

namespace App\Entity;

use App\Repository\DeviseRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=DeviseRepository::class)
 */
class Devise
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"ref", "get-by-bdc", "fiche-client"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"ref", "get-by-bdc", "fiche-client"})
     */
    private $libelle;

    /**
     * @ORM\ManyToOne(targetEntity=PaysFacturation::class, inversedBy="devises")
     * @ORM\JoinColumn(nullable=false)
     */
    private $paysFacturation;

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

    public function getPaysFacturation(): ?PaysFacturation
    {
        return $this->paysFacturation;
    }

    public function setPaysFacturation(?PaysFacturation $paysFacturation): self
    {
        $this->paysFacturation = $paysFacturation;

        return $this;
    }
}
