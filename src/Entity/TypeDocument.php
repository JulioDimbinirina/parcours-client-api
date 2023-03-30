<?php

namespace App\Entity;

use App\Repository\TypeDocumentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=TypeDocumentRepository::class)
 */
class TypeDocument
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ({"type-doc", "document", "status:lead"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups ({"type-doc", "document", "status:lead"})
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=ClientDocument::class, mappedBy="typeDocument")
     */
    private $clientDocuments;

    /**
     * @ORM\OneToMany(targetEntity=BdcDocument::class, mappedBy="typeDocument")
     */
    private $bdcDocuments;

    public function __construct()
    {
        $this->clientDocuments = new ArrayCollection();
        $this->bdcDocuments = new ArrayCollection();
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
            $clientDocument->setTypeDocument($this);
        }

        return $this;
    }

    public function removeClientDocument(ClientDocument $clientDocument): self
    {
        if ($this->clientDocuments->removeElement($clientDocument)) {
            // set the owning side to null (unless already changed)
            if ($clientDocument->getTypeDocument() === $this) {
                $clientDocument->setTypeDocument(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|BdcDocument[]
     */
    public function getBdcDocuments(): Collection
    {
        return $this->bdcDocuments;
    }

    public function addBdcDocument(BdcDocument $bdcDocument): self
    {
        if (!$this->bdcDocuments->contains($bdcDocument)) {
            $this->bdcDocuments[] = $bdcDocument;
            $bdcDocument->setTypeDocument($this);
        }

        return $this;
    }

    public function removeBdcDocument(BdcDocument $bdcDocument): self
    {
        if ($this->bdcDocuments->removeElement($bdcDocument)) {
            // set the owning side to null (unless already changed)
            if ($bdcDocument->getTypeDocument() === $this) {
                $bdcDocument->setTypeDocument(null);
            }
        }

        return $this;
    }
}
