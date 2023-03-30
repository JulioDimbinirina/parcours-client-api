<?php

namespace App\Entity;

use App\Repository\WorkflowLeadRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=WorkflowLeadRepository::class)
 */
class WorkflowLead
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"post:read", "del-fq"})
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"post:read", "del-fq"})
     */
    private $statut;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"post:read", "del-fq"})
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity=Customer::class, inversedBy="workflowLeads")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"post:read", "del-fq"})
     */
    private $client;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatut(): ?int
    {
        return $this->statut;
    }

    public function setStatut(int $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getClient(): ?Customer
    {
        return $this->client;
    }

    public function setClient(?Customer $client): self
    {
        $this->client = $client;

        return $this;
    }
}
