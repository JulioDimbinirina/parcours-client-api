<?php

namespace App\Entity;

use App\Repository\PotentielTransformationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=PotentielTransformationRepository::class)
 */
class PotentielTransformation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("post:read")
     * @Groups({"get-fq-id"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("post:read")
     * @Groups({"get-fq-id"})
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=ResumeLead::class, mappedBy="potentielTransformation", orphanRemoval=true)
     */
    private $resumeLeads;

    public function __construct()
    {
        $this->resumeLeads = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

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
            $resumeLead->setPotentielTransformation($this);
        }

        return $this;
    }

    public function removeResumeLead(ResumeLead $resumeLead): self
    {
        if ($this->resumeLeads->removeElement($resumeLead)) {
            // set the owning side to null (unless already changed)
            if ($resumeLead->getPotentielTransformation() === $this) {
                $resumeLead->setPotentielTransformation(null);
            }
        }

        return $this;
    }
}
