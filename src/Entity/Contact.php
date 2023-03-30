<?php

namespace App\Entity;

use App\Repository\ContactRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ContactRepository::class)
 */
class Contact
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"contact", "contact-att", "bdcs", "status:lead", "input", "sendtosign", "fiche-client", "get-fq-id", "interlocuteur"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=45)
     * @Groups({"contact", "contact-att", "sendtosign", "fiche-client", "input", "status:lead"})
     */
    private $civilite;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"contact", "contact-att", "sendtosign", "fiche-client", "bdcs", "status:lead", "input", "get-fq-id", "interlocuteur"})
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"contact", "contact-att", "sendtosign", "fiche-client", "bdcs", "status:lead", "input", "get-fq-id", "interlocuteur"})
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"contact", "contact-att", "fiche-client", "input", "get-fq-id", "interlocuteur"})
     */
    private $fonction;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"contact", "contact-att", "sendtosign", "fiche-client", "status:lead", "input", "bdcs"})
     */
    private $tel;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"contact", "contact-att", "get-by-bdc", "bdcs", "status:lead", "sendtosign", "fiche-client", "bdcs", "status:lead", "input"})
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"contact", "contact-att", "fiche-client", "input"})
     */
    private $skype;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"contact", "contact-att", "get-by-bdc", "bdcs", "status:lead", "sendtosign", "fiche-client", "bdcs", "status:lead", "input"})
     */
    private $status;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"contact", "contact-att", "get-by-bdc", "sendtosign", "fiche-client", "input"})
     */
    private $isCopieFacture;

    /**
     * @ORM\ManyToOne(targetEntity=Customer::class, inversedBy="contacts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $customer;

    /**
     * @ORM\OneToMany(targetEntity=ContactHasProfilContact::class, mappedBy="contact", orphanRemoval="true")
     * @Groups({"contact", "has", "contact-profil-contact", "get-fq-id", "input"})
     */
    private $contactHasProfilContacts;

    /**
     * @ORM\OneToMany(targetEntity=Historique::class, mappedBy="contact_id")
     */
    private $historiques;

    public function __construct()
    {
        $this->contactHasProfilContacts = new ArrayCollection();
        $this->historiques = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCivilite(): ?string
    {
        return $this->civilite;
    }

    public function setCivilite(string $civilite): self
    {
        $this->civilite = $civilite;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getFonction(): ?string
    {
        return $this->fonction;
    }

    public function setFonction(?string $fonction): self
    {
        $this->fonction = $fonction;

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

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(?bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getSkype(): ?string
    {
        return $this->skype;
    }

    public function setSkype(?string $skype): self
    {
        $this->skype = $skype;

        return $this;
    }

    public function getIsCopieFacture(): ?int
    {
        return $this->isCopieFacture;
    }

    public function setIsCopieFacture(?int $isCopieFacture): self
    {
        $this->isCopieFacture = $isCopieFacture;

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
     * @return Collection|ContactHasProfilContact[]
     */
    public function getContactHasProfilContacts(): Collection
    {
        return $this->contactHasProfilContacts;
    }

    public function addContactHasProfilContact(ContactHasProfilContact $contactHasProfilContact): self
    {
        if (!$this->contactHasProfilContacts->contains($contactHasProfilContact)) {
            $this->contactHasProfilContacts[] = $contactHasProfilContact;
            $contactHasProfilContact->setContact($this);
        }

        return $this;
    }

    public function removeContactHasProfilContact(ContactHasProfilContact $contactHasProfilContact): self
    {
        if ($this->contactHasProfilContacts->removeElement($contactHasProfilContact)) {
            // set the owning side to null (unless already changed)
            if ($contactHasProfilContact->getContact() === $this) {
                $contactHasProfilContact->setContact(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Historique[]
     */
    public function getHistoriques(): Collection
    {
        return $this->historiques;
    }

    public function addHistorique(Historique $historique): self
    {
        if (!$this->historiques->contains($historique)) {
            $this->historiques[] = $historique;
            $historique->setContactId($this);
        }

        return $this;
    }

    public function removeHistorique(Historique $historique): self
    {
        if ($this->historiques->removeElement($historique)) {
            // set the owning side to null (unless already changed)
            if ($historique->getContactId() === $this) {
                $historique->setContactId(null);
            }
        }

        return $this;
    }

}
