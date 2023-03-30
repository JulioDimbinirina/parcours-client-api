<?php

namespace App\Entity;

use App\Repository\BudgetAnnuelRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BudgetAnnuelRepository::class)
 */
class BudgetAnnuel
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $annee;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $pays;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $bu;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $caAnnuelNplusUn;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $caJanvier;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $caFevrier;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $caMars;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $caAvril;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $caMai;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $caJuin;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $caJuillet;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $caAout;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $caSeptembre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $caOctobre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $caNovembre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $caDecembre;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAnnee(): ?string
    {
        return $this->annee;
    }

    public function setAnnee(?string $annee): self
    {
        $this->annee = $annee;

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

    public function getBu(): ?string
    {
        return $this->bu;
    }

    public function setBu(?string $bu): self
    {
        $this->bu = $bu;

        return $this;
    }

    public function getCaAnnuelNplusUn(): ?string
    {
        return $this->caAnnuelNplusUn;
    }

    public function setCaAnnuelNplusUn(?string $caAnnuelNplusUn): self
    {
        $this->caAnnuelNplusUn = $caAnnuelNplusUn;

        return $this;
    }

    public function getCaJanvier(): ?string
    {
        return $this->caJanvier;
    }

    public function setCaJanvier(?string $caJanvier): self
    {
        $this->caJanvier = $caJanvier;

        return $this;
    }

    public function getCaFevrier(): ?string
    {
        return $this->caFevrier;
    }

    public function setCaFevrier(?string $caFevrier): self
    {
        $this->caFevrier = $caFevrier;

        return $this;
    }

    public function getCaMars(): ?string
    {
        return $this->caMars;
    }

    public function setCaMars(?string $caMars): self
    {
        $this->caMars = $caMars;

        return $this;
    }

    public function getCaAvril(): ?string
    {
        return $this->caAvril;
    }

    public function setCaAvril(?string $caAvril): self
    {
        $this->caAvril = $caAvril;

        return $this;
    }

    public function getCaMai(): ?string
    {
        return $this->caMai;
    }

    public function setCaMai(?string $caMai): self
    {
        $this->caMai = $caMai;

        return $this;
    }

    public function getCaJuin(): ?string
    {
        return $this->caJuin;
    }

    public function setCaJuin(?string $caJuin): self
    {
        $this->caJuin = $caJuin;

        return $this;
    }

    public function getCaJuillet(): ?string
    {
        return $this->caJuillet;
    }

    public function setCaJuillet(?string $caJuillet): self
    {
        $this->caJuillet = $caJuillet;

        return $this;
    }

    public function getCaAout(): ?string
    {
        return $this->caAout;
    }

    public function setCaAout(?string $caAout): self
    {
        $this->caAout = $caAout;

        return $this;
    }

    public function getCaSeptembre(): ?string
    {
        return $this->caSeptembre;
    }

    public function setCaSeptembre(?string $caSeptembre): self
    {
        $this->caSeptembre = $caSeptembre;

        return $this;
    }

    public function getCaOctobre(): ?string
    {
        return $this->caOctobre;
    }

    public function setCaOctobre(?string $caOctobre): self
    {
        $this->caOctobre = $caOctobre;

        return $this;
    }

    public function getCaNovembre(): ?string
    {
        return $this->caNovembre;
    }

    public function setCaNovembre(?string $caNovembre): self
    {
        $this->caNovembre = $caNovembre;

        return $this;
    }

    public function getCaDecembre(): ?string
    {
        return $this->caDecembre;
    }

    public function setCaDecembre(?string $caDecembre): self
    {
        $this->caDecembre = $caDecembre;

        return $this;
    }
}
