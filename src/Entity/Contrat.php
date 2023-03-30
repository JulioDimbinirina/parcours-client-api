<?php

namespace App\Entity;

use App\Repository\ContratRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ContratRepository::class)
 */
class Contrat
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateContrat;

    /**
     * @ORM\Column(type="integer")
     */
    private $idCustomer;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $texte;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $statusContrat;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateSignature;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $signaturePackContratCustomer;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateContrat(): ?\DateTimeInterface
    {
        return $this->dateContrat;
    }

    public function setDateContrat(\DateTimeInterface $dateContrat): self
    {
        $this->dateContrat = $dateContrat;

        return $this;
    }

    public function getIdCustomer(): ?int
    {
        return $this->idCustomer;
    }

    public function setIdCustomer(int $idCustomer): self
    {
        $this->idCustomer = $idCustomer;

        return $this;
    }

    public function getTexte(): ?string
    {
        return $this->texte;
    }

    public function setTexte(?string $texte): self
    {
        $this->texte = $texte;

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

    public function getDateSignature(): ?\DateTimeInterface
    {
        return $this->dateSignature;
    }

    public function setDateSignature(?\DateTimeInterface $dateSignature): self
    {
        $this->dateSignature = $dateSignature;

        return $this;
    }

    public function getSignaturePackContratCustomer(): ?string
    {
        return $this->signaturePackContratCustomer;
    }

    public function setSignaturePackContratCustomer(?string $signaturePackContratCustomer): self
    {
        $this->signaturePackContratCustomer = $signaturePackContratCustomer;

        return $this;
    }
}
