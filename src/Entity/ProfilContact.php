<?php

namespace App\Entity;

use App\Repository\ProfilContactRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ProfilContactRepository::class)
 */
class ProfilContact
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"profil-contact", "contact-profil-contact", "get-fq-id", "input"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     * @Groups({"profil-contact", "contact-profil-contact", "get-fq-id", "input"})
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=ContactHasProfilContact::class, mappedBy="profilContact")
     */
    private $contactHasProfilContacts;

    public function __construct()
    {
        $this->contactHasProfilContacts = new ArrayCollection();
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
            $contactHasProfilContact->setProfilContact($this);
        }

        return $this;
    }

    public function removeContactHasProfilContact(ContactHasProfilContact $contactHasProfilContact): self
    {
        if ($this->contactHasProfilContacts->removeElement($contactHasProfilContact)) {
            // set the owning side to null (unless already changed)
            if ($contactHasProfilContact->getProfilContact() === $this) {
                $contactHasProfilContact->setProfilContact(null);
            }
        }

        return $this;
    }
}
