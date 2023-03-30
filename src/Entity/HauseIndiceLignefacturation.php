<?php

namespace App\Entity;

use App\Repository\HauseIndiceLignefacturationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=HauseIndiceLignefacturationRepository::class)
 */
class HauseIndiceLignefacturation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $id_operation;

    /**
     * @ORM\Column(type="decimal", precision=50, scale=2, nullable=true)
     */
    private $ancienPrix;

    /**
     * @ORM\Column(type="decimal", precision=50, scale=2, nullable=true)
     */
    private $nouveauPrix;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $hausseIndeceClient_id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $dateAplicatif;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=0, nullable=true)
     */
    private $ancienPrixActe;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=0, nullable=true)
     */
    private $nouveauPrixActe;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=0, nullable=true)
     */
    private $ancienPrixHeure;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=0, nullable=true)
     */
    private $nouveauPrixHeure;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $commentaireModManuel;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdOperation(): ?int
    {
        return $this->id_operation;
    }

    public function setIdOperation(?int $id_operation): self
    {
        $this->id_operation = $id_operation;

        return $this;
    }

    public function getAncienPrix(): ?string
    {
        return $this->ancienPrix;
    }

    public function setAncienPrix(?string $ancienPrix): self
    {
        $this->ancienPrix = $ancienPrix;

        return $this;
    }

    public function getNouveauPrix(): ?string
    {
        return $this->nouveauPrix;
    }

    public function setNouveauPrix(?string $nouveauPrix): self
    {
        $this->nouveauPrix = $nouveauPrix;

        return $this;
    }

    public function getHausseIndeceClientId(): ?int
    {
        return $this->hausseIndeceClient_id;
    }

    public function setHausseIndeceClientId(?int $hausseIndeceClient_id): self
    {
        $this->hausseIndeceClient_id = $hausseIndeceClient_id;

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

    public function getAncienPrixActe(): ?string
    {
        return $this->ancienPrixActe;
    }

    public function setAncienPrixActe(?string $ancienPrixActe): self
    {
        $this->ancienPrixActe = $ancienPrixActe;

        return $this;
    }

    public function getNouveauPrixActe(): ?string
    {
        return $this->nouveauPrixActe;
    }

    public function setNouveauPrixActe(?string $nouveauPrixActe): self
    {
        $this->nouveauPrixActe = $nouveauPrixActe;

        return $this;
    }

    public function getAncienPrixHeure(): ?string
    {
        return $this->ancienPrixHeure;
    }

    public function setAncienPrixHeure(?string $ancienPrixHeure): self
    {
        $this->ancienPrixHeure = $ancienPrixHeure;

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

    public function getCommentaireModManuel(): ?string
    {
        return $this->commentaireModManuel;
    }

    public function setCommentaireModManuel(?string $commentaireModManuel): self
    {
        $this->commentaireModManuel = $commentaireModManuel;

        return $this;
    }
}
