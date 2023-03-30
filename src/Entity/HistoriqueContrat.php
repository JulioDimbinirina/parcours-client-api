<?php

namespace App\Entity;

use App\Repository\HistoriqueContratRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HistoriqueContratRepository::class)
 */
class HistoriqueContrat
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $statusContrat;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $idContrat;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getStatusContrat(): ?int
    {
        return $this->statusContrat;
    }

    public function setStatusContrat(?int $statusContrat): self
    {
        $this->statusContrat = $statusContrat;

        return $this;
    }

    public function getIdContrat(): ?int
    {
        return $this->idContrat;
    }

    public function setIdContrat(?int $idContrat): self
    {
        $this->idContrat = $idContrat;

        return $this;
    }
}
