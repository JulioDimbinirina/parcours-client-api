<?php

namespace App\Entity;

use App\Repository\ContactHasProfilContactRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ContactHasProfilContactRepository::class)
 */
class ContactHasProfilContact
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"has"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Contact::class, inversedBy="contactHasProfilContacts")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     * @Groups({"has"})
     */
    private $contact;

    /**
     * @ORM\ManyToOne(targetEntity=ProfilContact::class, inversedBy="contactHasProfilContacts")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     * @Groups({"has", "contact-profil-contact", "get-fq-id", "input"})
     */
    private $profilContact;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContact(): ?Contact
    {
        return $this->contact;
    }

    public function setContact(?Contact $contact): self
    {
        $this->contact = $contact;

        return $this;
    }

    public function getProfilContact(): ?ProfilContact
    {
        return $this->profilContact;
    }

    public function setProfilContact(?ProfilContact $profilContact): self
    {
        $this->profilContact = $profilContact;

        return $this;
    }
}
