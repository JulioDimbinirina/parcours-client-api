<?php

namespace App\Entity;

use App\Repository\CustomerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CustomerRepository::class)
 */
class Customer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"customer", "contact", "update", "last", "bdcs", "status:lead", "input", "bdc:lead", "sendtosign", "fiche-client", "via-irm", "get-fq-id", "get-by-bdc", "all:ref", "saisie-acte", "inject:cout"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"customer", "contact", "update", "get-by-bdc", "bdcs", "status:lead", "input", "fiche-client", "via-irm"})
     */
    private $numClient;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"customer", "contact", "update", "get-by-bdc", "bdcs", "status:lead", "input", "fiche-client", "via-irm", "get-fq-id", "saisie-acte", "inject:cout", "saisie:manager"})
     */
    private $raisonSocial;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"customer", "contact", "update", "get-by-bdc", "status:lead", "input", "fiche-client"})
     */
    private $marqueCommercial;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"customer", "contact", "update", "get-by-bdc", "status:lead", "input", "fiche-client"})
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     * @Groups({"customer", "contact", "update", "input"})
     */
    private $cp;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"customer", "contact", "update", "input"})
     */
    private $ville;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"customer", "contact", "update", "input"})
     */
    private $siteWeb;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"customer", "contact", "update", "fiche-client", "input"})
     */
    private $tel;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Groups({"customer", "contact", "update", "get-by-bdc", "input"})
     */
    private $isAdressFactDiff;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"customer", "contact", "update", "input"})
     */
    private $pays;

    /**
     * @ORM\ManyToOne(targetEntity=CategorieClient::class, inversedBy="customers", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"customer", "cat-attr", "get-by-bdc", "status:lead", "input"})
     */
    private $categorieClient;

    /**
     * @ORM\ManyToOne(targetEntity=MappingClient::class, inversedBy="customers", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     * @Groups({"customer", "mapp-attr", "status:lead", "input", "fiche-client"})
     */
    private $mappingClient;

    /**
     * @ORM\OneToOne(targetEntity=AdresseFacturation::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     * @Groups({"customer", "get-by-bdc", "input"})
     */
    private $adresseFacturation;

    /**
     * @ORM\OneToMany(targetEntity=Contact::class, mappedBy="customer", orphanRemoval=true, cascade={"persist"})
     * @Groups({"contact-att", "get-by-bdc", "bdcs", "sendtosign", "fiche-client", "status:lead", "input"})
     */
    private $contacts;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="customers")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"bdcs", "fiche-client"})
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=ResumeLead::class, mappedBy="customer", orphanRemoval=true, cascade={"persist"})
     * @Groups({"fiche-client", "status:lead"})
     */
    private $resumeLeads;

    /**
     * @ORM\OneToMany(targetEntity=WorkflowLead::class, mappedBy="clientId")
     */
    private $workflowLeads;

    /**
     * @ORM\OneToOne(targetEntity=StatusLead::class, mappedBy="customer", cascade={"persist", "remove"})
     * @Groups("status:lead")
     */
    private $statusLead;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"customer", "contact", "update", "update-bdc", "get-by-bdc", "via-irm"})
     */
    private $irm;

    /**
     * @ORM\OneToMany(targetEntity=ClientDocument::class, mappedBy="customer")
     * @Groups({"status:lead"})
     */
    private $clientDocuments;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"customer", "contact", "update"})
     */
    private $sageCompteTiers;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"customer", "contact", "update"})
     */
    private $sageCompteCollectif;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"customer", "contact", "update"})
     */
    private $sageCategorieComptable;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isHasContract;

    public function __construct()
    {
        $this->contacts = new ArrayCollection();
        $this->resumeLeads = new ArrayCollection();
        $this->workflowLeads = new ArrayCollection();
        $this->clientDocuments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public  function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getNumClient(): ?string
    {
        return $this->numClient;
    }

    public function setNumClient(?string $numClient): self
    {
        $this->numClient = $numClient;
        return $this;
    }

    public function getRaisonSocial(): ?string
    {
        return $this->raisonSocial;
    }

    public function setRaisonSocial(string $raisonSocial): self
    {
        $this->raisonSocial = $raisonSocial;

        return $this;
    }

    public function getMarqueCommercial(): ?string
    {
        return $this->marqueCommercial;
    }

    public function setMarqueCommercial(string $marqueCommercial): self
    {
        $this->marqueCommercial = $marqueCommercial;

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

    public function getSiteWeb(): ?string
    {
        return $this->siteWeb;
    }

    public function setSiteWeb(?string $siteWeb): self
    {
        $this->siteWeb = $siteWeb;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(?string $tel): self
    {
        $this->tel = $tel;

        return $this;
    }

    public function getIsAdressFactDiff(): ?string
    {
        return $this->isAdressFactDiff;
    }

    public function setIsAdressFactDiff(?string $isAdressFactDiff): self
    {
        $this->isAdressFactDiff = $isAdressFactDiff;

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

    public function getCategorieClient(): ?CategorieClient
    {
        return $this->categorieClient;
    }

    public function setCategorieClient(?CategorieClient $categorieClient): self
    {
        $this->categorieClient = $categorieClient;

        return $this;
    }

    public function getMappingClient(): ?MappingClient
    {
        return $this->mappingClient;
    }

    public function setMappingClient(?MappingClient $mappingClient): self
    {
        $this->mappingClient = $mappingClient;

        return $this;
    }

    public function getAdresseFacturation(): ?AdresseFacturation
    {
        return $this->adresseFacturation;
    }

    public function setAdresseFacturation(?AdresseFacturation $adresseFacturation): self
    {
        $this->adresseFacturation = $adresseFacturation;

        return $this;
    }

    /**
     * @return Collection|Contact[]
     */
    public function getContacts(): Collection
    {
        return $this->contacts;
    }

    public function addContact(Contact $contact): self
    {
        if (!$this->contacts->contains($contact)) {
            $this->contacts[] = $contact;
            $contact->setCustomer($this);
        }

        return $this;
    }

    public function removeContact(Contact $contact): self
    {
        if ($this->contacts->removeElement($contact)) {
            // set the owning side to null (unless already changed)
            if ($contact->getCustomer() === $this) {
                $contact->setCustomer(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|ResumeLead[]
     */
    public function getResumeLeads(): Collection
    {
        return $this->resumeLeads;
    }

    public function addResumeLead(ResumeLead $resumeLead): self
    {
        if (!$this->resumeLeads->contains($resumeLead)) {
            $this->resumeLeads[] = $resumeLead;
            $resumeLead->setCustomer($this);
        }

        return $this;
    }

    public function removeResumeLead(ResumeLead $resumeLead): self
    {
        if ($this->resumeLeads->removeElement($resumeLead)) {
            // set the owning side to null (unless already changed)
            if ($resumeLead->getCustomer() === $this) {
                $resumeLead->setCustomer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|WorkflowLead[]
     */
    public function getWorkflowLeads(): Collection
    {
        return $this->workflowLeads;
    }

    public function addWorkflowLead(WorkflowLead $workflowLead): self
    {
        if (!$this->workflowLeads->contains($workflowLead)) {
            $this->workflowLeads[] = $workflowLead;
            $workflowLead->setClientId($this);
        }

        return $this;
    }

    public function removeWorkflowLead(WorkflowLead $workflowLead): self
    {
        if ($this->workflowLeads->removeElement($workflowLead)) {
            // set the owning side to null (unless already changed)
            if ($workflowLead->getClientId() === $this) {
                $workflowLead->setClientId(null);
            }
        }

        return $this;
    }

    public function getStatusLead(): ?StatusLead
    {
        return $this->statusLead;
    }

    public function setStatusLead(StatusLead $statusLead): self
    {
        // set the owning side of the relation if necessary
        if ($statusLead->getCustomer() !== $this) {
            $statusLead->setCustomer($this);
        }

        $this->statusLead = $statusLead;

        return $this;
    }

    /**
     * @return Collection|ClientDocument[]
     */
    public function getClientDocuments(): Collection
    {
        return $this->clientDocuments;
    }

    public function addClientDocument(ClientDocument $clientDocument): self
    {
        if (!$this->clientDocuments->contains($clientDocument)) {
            $this->clientDocuments[] = $clientDocument;
            $clientDocument->setCustomer($this);
        }

        return $this;
    }

    public function removeClientDocument(ClientDocument $clientDocument): self
    {
        if ($this->clientDocuments->removeElement($clientDocument)) {
            // set the owning side to null (unless already changed)
            if ($clientDocument->getCustomer() === $this) {
                $clientDocument->setCustomer(null);
            }
        }

        return $this;
    }

    public function getIrm(): ?string
    {
        return $this->irm;
    }

    public function setIrm(?string $irm): self
    {
        $this->irm = $irm;
        return $this;
    }

    public function getSageCompteTiers(): ?string
    {
        return $this->sageCompteTiers;
    }

    public function setSageCompteTiers(?string $sageCompteTiers): self
    {
        $this->sageCompteTiers = $sageCompteTiers;
        return $this;
    }

    public function getSageCompteCollectif(): ?string
    {
        return $this->sageCompteCollectif;
    }

    public function setSageCompteCollectif(?string $sageCompteCollectif): self
    {
        $this->sageCompteCollectif = $sageCompteCollectif;
        return $this;
    }

    public function getSageCategorieComptable(): ?string
    {
        return $this->sageCategorieComptable;
    }

    public function setSageCategorieComptable(?string $sageCategorieComptable): self
    {
        $this->sageCategorieComptable = $sageCategorieComptable;
        return $this;
    }

    public function getIsHasContract(): ?bool
    {
        return $this->isHasContract;
    }

    public function setIsHasContract(?bool $isHasContract): self
    {
        $this->isHasContract = $isHasContract;

        return $this;
    }
}
