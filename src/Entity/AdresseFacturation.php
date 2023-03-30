<?php

namespace App\Entity;

use App\Repository\AdresseFacturationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=AdresseFacturationRepository::class)
 */
class AdresseFacturation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"customer", "get-by-bdc"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"customer", "get-by-bdc"})
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     * @Groups({"customer", "get-by-bdc"})
     */
    private $cp;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"customer", "get-by-bdc"})
     */
    private $ville;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"customer", "get-by-bdc"})
     */
    private $pays;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCp(): ?string
    {
        return $this->cp;
    }

    public function setCp(?string $cp): self
    {
        $this->cp = $cp;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(?string $pays): self
    {
        $this->pays = $pays;

        return $this;
    }
}
