<?php

namespace App\Entity;

use App\Repository\TvaRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=TvaRepository::class)
 */
class Tva
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"ref", "get-by-bdc"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"ref", "get-by-bdc"})
     */
    private $libelle;

    /**
     * @ORM\ManyToOne(targetEntity=PaysFacturation::class, inversedBy="tvas")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"ref"})
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
