<?php

namespace App\Entity;

use App\Repository\SocieteFacturationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=SocieteFacturationRepository::class)
 */
class SocieteFacturation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"ref", "get-by-bdc", "bdcs", "status:lead"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     * @Groups({"ref", "get-by-bdc"})
     * @Groups({"bdcs", "bdcs", "status:lead"})
     */
    private $libelle;

    /**
     * @ORM\ManyToOne(targetEntity=PaysFacturation::class, inversedBy="societeFacturations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $paysFacturation;

    /**
     * @ORM\OneToMany(targetEntity=Bdc::class, mappedBy="societeFacturation")
     */
    private $bdcs;

    /**
     * @ORM\OneToMany(targetEntity=CoordoneBancaire::class, mappedBy="societeFacturation")
     */
    private $coordoneBancaires;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $activite;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $formeJuridique;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $capital;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $codePostal;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ville;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $registreCommerce;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $identifiantFiscal;

    public function __construct()
    {
        $this->bdcs = new ArrayCollection();
        $this->coordoneBancaires = new ArrayCollection();
    }

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

    /**
     * @return Collection|Bdc[]
     */
    public function getBdcs(): Collection
    {
        return $this->bdcs;
    }

    public function addBdc(Bdc $bdc): self
    {
        if (!$this->bdcs->contains($bdc)) {
            $this->bdcs[] = $bdc;
            $bdc->setSocieteFacturation($this);
        }

        return $this;
    }

    public function removeBdc(Bdc $bdc): self
    {
        if ($this->bdcs->removeElement($bdc)) {
            // set the owning side to null (unless already changed)
            if ($bdc->getSocieteFacturation() === $this) {
                $bdc->setSocieteFacturation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CoordoneBancaire[]
     */
    public function getCoordoneBancaires(): Collection
    {
        return $this->coordoneBancaires;
    }

    public function addCoordoneBancaire(CoordoneBancaire $coordoneBancaire): self
    {
        if (!$this->coordoneBancaires->contains($coordoneBancaire)) {
            $this->coordoneBancaires[] = $coordoneBancaire;
            $coordoneBancaire->setSocieteFacturation($this);
        }

        return $this;
    }

    public function removeCoordoneBancaire(CoordoneBancaire $coordoneBancaire): self
    {
        if ($this->coordoneBancaires->removeElement($coordoneBancaire)) {
            // set the owning side to null (unless already changed)
            if ($coordoneBancaire->getSocieteFacturation() === $this) {
                $coordoneBancaire->setSocieteFacturation(null);
            }
        }

        return $this;
    }

    public function getActivite(): ?string
    {
        return $this->activite;
    }

    public function setActivite(?string $activite): self
    {
        $this->activite = $activite;

        return $this;
    }

    public function getFormeJuridique(): ?string
    {
        return $this->formeJuridique;
    }

    public function setFormeJuridique(?string $formeJuridique): self
    {
        $this->formeJuridique = $formeJuridique;

        return $this;
    }

    public function getCapital(): ?string
    {
        return $this->capital;
    }

    public function setCapital(?string $capital): self
    {
        $this->capital = $capital;

        return $this;
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

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function setCodePostal(?string $codePostal): self
    {
        $this->codePostal = $codePostal;

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

    public function getRegistreCommerce(): ?string
    {
        return $this->registreCommerce;
    }

    public function setRegistreCommerce(?string $registreCommerce): self
    {
        $this->registreCommerce = $registreCommerce;

        return $this;
    }

    public function getIdentifiantFiscal(): ?string
    {
        return $this->identifiantFiscal;
    }

    public function setIdentifiantFiscal(?string $identifiantFiscal): self
    {
        $this->identifiantFiscal = $identifiantFiscal;

        return $this;
    }
}
