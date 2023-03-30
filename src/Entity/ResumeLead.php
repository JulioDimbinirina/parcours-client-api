<?php

namespace App\Entity;

use App\Repository\ResumeLeadRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ResumeLeadRepository::class)
 */
class ResumeLead
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"post:read", "bdcs", "bdc:lead", "get-fq-id", "get-by-bdc", "sendtosign", "fiche-client", "status:lead", "inject:cout"})
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     * @Groups({"get-fq-id", "post:read"})
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="string", length=54, nullable=true)
     * @Groups({"get-fq-id", "post:read"})
     */
    private $typeOffre;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"get-fq-id", "post:read"})
     */
    private $resumePrestation;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     * @Groups({"get-fq-id", "post:read", "fiche-client"})
     */
    private $potentielCA;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Groups({"get-fq-id", "post:read"})
     */
    private $sepContactClient;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({"get-fq-id", "post:read"})
     */
    private $niveauUrgence;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Groups({"get-fq-id", "post:read"})
     */
    private $isFormationFacturable;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"get-fq-id", "post:read"})
     */
    private $delaiRemiseOffre;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"get-fq-id", "post:read"})
     */
    private $dateDemarrage;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Groups({"get-fq-id", "post:read"})
     */
    private $isOutilFournis;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"get-fq-id", "post:read"})
     */
    private $percisionClient;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"get-fq-id", "post:read"})
     */
    private $pointVigilance;

    /**
     * @ORM\Column(type="json", nullable=true)
     * @Groups({"get-fq-id", "post:read"})
     */
    private $piecesJointes = [];

    /**
     * @ORM\ManyToOne(targetEntity=OriginLead::class, inversedBy="resumeLeads")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"get-fq-id", "post:read"})
     */
    private $originLead;

    /**
     * @ORM\ManyToOne(targetEntity=DureeTrt::class, inversedBy="resumeLeads")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"get-fq-id", "post:read", "fiche-client", "bdcs", "status:lead"})
     */
    private $dureeTrt;

    /**
     * @ORM\ManyToOne(targetEntity=PotentielTransformation::class, inversedBy="resumeLeads")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"get-fq-id", "post:read"})
     */
    private $potentielTransformation;

    /**
     * @ORM\OneToMany(targetEntity=LeadDetailOperation::class, mappedBy="resumeLead", orphanRemoval=true, cascade={"persist"})
     * @Groups({"get-fq-id", "fiche-client"})
     */
    private $leadDetailOperations;

    /**
     * @ORM\ManyToOne(targetEntity=Customer::class, inversedBy="resumeLeads")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"get-by-bdc", "bdcs", "sendtosign", "via-irm", "get-fq-id", "inject:cout", "saisie:manager"})
     */
    private $customer;

    /**
     * @ORM\OneToMany(targetEntity=Bdc::class, mappedBy="resumeLead", cascade={"persist"})
     * @Groups({"fiche-client", "status:lead"})
     */
    private $bdcs;

    /**
     * @ORM\Column(type="array", nullable=true)
     * @Groups({"get-fq-id"})
     */
    private $interlocuteur = [];

    public function __construct()
    {
        $this->leadDetailOperations = new ArrayCollection();
        $this->bdcs = new ArrayCollection();
        $this->contacts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getTypeOffre(): ?string
    {
        return $this->typeOffre;
    }

    public function setTypeOffre(?string $typeOffre): self
    {
        $this->typeOffre = $typeOffre;

        return $this;
    }

    public function getResumePrestation(): ?string
    {
        return $this->resumePrestation;
    }

    public function setResumePrestation(?string $resumePrestation): self
    {
        $this->resumePrestation = $resumePrestation;

        return $this;
    }

    public function getPotentielCA(): ?string
    {
        return $this->potentielCA;
    }

    public function setPotentielCA(?string $potentielCA): self
    {
        $this->potentielCA = $potentielCA;

        return $this;
    }

    public function getSepContactClient(): ?string
    {
        return $this->sepContactClient;
    }

    public function setSepContactClient(?string $sepContactClient): self
    {
        $this->sepContactClient = $sepContactClient;

        return $this;
    }

    public function getNiveauUrgence(): ?string
    {
        return $this->niveauUrgence;
    }

    public function setNiveauUrgence(string $niveauUrgence): self
    {
        $this->niveauUrgence = $niveauUrgence;

        return $this;
    }

    public function getDelaiRemiseOffre(): ?\DateTimeInterface
    {
        return $this->delaiRemiseOffre;
    }

    public function setDelaiRemiseOffre(?\DateTimeInterface $delaiRemiseOffre): self
    {
        $this->delaiRemiseOffre = $delaiRemiseOffre;

        return $this;
    }

    public function getDateDemarrage(): ?\DateTimeInterface
    {
        return $this->dateDemarrage;
    }

    public function setDateDemarrage(?\DateTimeInterface $dateDemarrage): self
    {
        $this->dateDemarrage = $dateDemarrage;

        return $this;
    }

    public function getIsOutilFournis(): ?string
    {
        return $this->isOutilFournis;
    }

    public function setIsOutilFournis(?string $isOutilFournis): self
    {
        $this->isOutilFournis = $isOutilFournis;

        return $this;
    }

    public function getIsFormationFacturable(): ?bool
    {
        return $this->isFormationFacturable;
    }

    public function setIsFormationFacturable(?bool $isFormationFacturable): self
    {
        $this->isFormationFacturable = $isFormationFacturable;

        return $this;
    }

    public function getPercisionClient(): ?string
    {
        return $this->percisionClient;
    }

    public function setPercisionClient(?string $percisionClient): self
    {
        $this->percisionClient = $percisionClient;

        return $this;
    }

    public function getPointVigilance(): ?string
    {
        return $this->pointVigilance;
    }

    public function setPointVigilance(?string $pointVigilance): self
    {
        $this->pointVigilance = $pointVigilance;

        return $this;
    }

    public function getPiecesJointes(): ?array
    {
        return $this->piecesJointes;
    }

    public function setPiecesJointes(?array $piecesJointes): self
    {
        $this->piecesJointes = $piecesJointes;

        return $this;
    }

    /**
     * @return Collection|LeadDetailOperation[]
     */
    public function getLeadDetailOperations(): Collection
    {
        return $this->leadDetailOperations;
    }

    public function addLeadDetailOperation(LeadDetailOperation $leadDetailOperation): self
    {
        if (!$this->leadDetailOperations->contains($leadDetailOperation)) {
            $this->leadDetailOperations[] = $leadDetailOperation;
            $leadDetailOperation->setResumeLead($this);
        }

        return $this;
    }

    public function removeLeadDetailOperation(LeadDetailOperation $leadDetailOperation): self
    {
        if ($this->leadDetailOperations->removeElement($leadDetailOperation)) {
            // set the owning side to null (unless already changed)
            if ($leadDetailOperation->getResumeLead() === $this) {
                $leadDetailOperation->setResumeLead(null);
            }
        }

        return $this;
    }

    public function getOriginLead(): ?OriginLead
    {
        return $this->originLead;
    }

    public function setOriginLead(?OriginLead $originLead): self
    {
        $this->originLead = $originLead;

        return $this;
    }

    public function getDureeTrt(): ?DureeTrt
    {
        return $this->dureeTrt;
    }

    public function setDureeTrt(?DureeTrt $dureeTrt): self
    {
        $this->dureeTrt = $dureeTrt;

        return $this;
    }

    public function getPotentielTransformation(): ?PotentielTransformation
    {
        return $this->potentielTransformation;
    }

    public function setPotentielTransformation(?PotentielTransformation $potentielTransformation): self
    {
        $this->potentielTransformation = $potentielTransformation;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

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
            $bdc->setResumeLead($this);
        }

        return $this;
    }

    public function removeBdc(Bdc $bdc): self
    {
        if ($this->bdcs->removeElement($bdc)) {
            // set the owning side to null (unless already changed)
            if ($bdc->getResumeLead() === $this) {
                $bdc->setResumeLead(null);
            }
        }

        return $this;
    }

    public function getInterlocuteur(): ?array
    {
        return $this->interlocuteur;
    }

    public function setInterlocuteur(?array $interlocuteur): self
    {
        $this->interlocuteur = $interlocuteur;

        return $this;
    }

}
