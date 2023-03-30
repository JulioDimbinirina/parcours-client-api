<?php

namespace App\Entity;

use App\Repository\HausseIndiceSyntecClientRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HausseIndiceSyntecClientRepository::class)
 */
class HausseIndiceSyntecClient
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_customer;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $isType;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $status;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateContrat;

    /**
     * @ORM\Column(type="integer")
     */
    private $clause;

    /**
     * @ORM\Column(type="decimal", precision=40, scale=3, nullable=true)
     */
    private $initial;

    /**
     * @ORM\Column(type="decimal", precision=40, scale=5, nullable=true)
     */
    private $actuel;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $tauxEvolution;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateYears;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $dateAplicatif;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=0, nullable=true)
     */
    private $nouveauPrixHeure;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $typePdf;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $commentaire;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(int $id): self
    {   
        $this->id=$id;
        return $this;
    }

    public function getIdCustomer(): ?int
    {
        return $this->id_customer;
    }

    public function setIdCustomer(int $id_customer): self
    {
        $this->id_customer = $id_customer;

        return $this;
    }

    public function getIsType(): ?int
    {
        return $this->isType;
    }

    public function setIsType(?int $isType): self
    {
        $this->isType = $isType;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getDateContrat(): ?\DateTimeInterface
    {
        return $this->dateContrat;
    }

    public function setDateContrat(?\DateTimeInterface $dateContrat): self
    {
        $this->dateContrat = $dateContrat;

        return $this;
    }

    public function getClause(): ?int
    {
        return $this->clause;
    }

    public function setClause(int $clause): self
    {
        $this->clause = $clause;

        return $this;
    }

    public function getInitial(): ?string
    {
        return $this->initial;
    }

    public function setInitial(?string $initial): self
    {
        $this->initial = $initial;

        return $this;
    }

    public function getActuel(): ?string
    {
        return $this->actuel;
    }

    public function setActuel(?string $actuel): self
    {
        $this->actuel = $actuel;

        return $this;
    }

    public function getTauxEvolution(): ?int
    {
        return $this->tauxEvolution;
    }

    public function setTauxEvolution(?int $tauxEvolution): self
    {
        $this->tauxEvolution = $tauxEvolution;

        return $this;
    }

    public function getDateYears(): ?\DateTimeInterface
    {
        return $this->dateYears;
    }

    public function setDateYears(?\DateTimeInterface $dateYears): self
    {
        $this->dateYears = $dateYears;

        return $this;
    }

    public function getDateAplicatif(): ?string
    {
        return $this->dateAplicatif;
    }

    public function setDateAplicatif(?string $dateAplicatif): self
    {
        $this->dateAplicatif = $dateAplicatif;

        return $this;
    }

    public function getNouveauPrixHeure(): ?string
    {
        return $this->nouveauPrixHeure;
    }

    public function setNouveauPrixHeure(?string $nouveauPrixHeure): self
    {
        $this->nouveauPrixHeure = $nouveauPrixHeure;

        return $this;
    }

    public function getTypePdf(): ?string
    {
        return $this->typePdf;
    }

    public function setTypePdf(?string $typePdf): self
    {
        $this->typePdf = $typePdf;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): self
    {
        $this->commentaire = $commentaire;

        return $this;
    }
}
