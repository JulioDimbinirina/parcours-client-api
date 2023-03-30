<?php

namespace App\Entity;

use App\Repository\BdcDocumentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=BdcDocumentRepository::class)
 */
class BdcDocument
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"document"})
     */
    private $id;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"document"})
     */
    private $dateSignature;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"document"})
     */
    private $dateDebutPriseCompte;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"document"})
     */
    private $dateFinPriseCompte;

    /**
     * @ORM\ManyToOne(targetEntity=Bdc::class, inversedBy="bdcDocuments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $bdc;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"document"})
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=TypeDocument::class, inversedBy="bdcDocuments")
     * @Groups({"document"})
     */
    private $typeDocument;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDateDebutPriseCompte(): ?\DateTimeInterface
    {
        return $this->dateDebutPriseCompte;
    }

    public function setDateDebutPriseCompte(?\DateTimeInterface $dateDebutPriseCompte): self
    {
        $this->dateDebutPriseCompte = $dateDebutPriseCompte;

        return $this;
    }

    public function getDateFinPriseCompte(): ?\DateTimeInterface
    {
        return $this->dateFinPriseCompte;
    }

    public function setDateFinPriseCompte(?\DateTimeInterface $dateFinPriseCompte): self
    {
        $this->dateFinPriseCompte = $dateFinPriseCompte;

        return $this;
    }

    public function getBdc(): ?Bdc
    {
        return $this->bdc;
    }

    public function setBdc(?Bdc $bdc): self
    {
        $this->bdc = $bdc;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getTypeDocument(): ?TypeDocument
    {
        return $this->typeDocument;
    }

    public function setTypeDocument(?TypeDocument $typeDocument): self
    {
        $this->typeDocument = $typeDocument;

        return $this;
    }
}
