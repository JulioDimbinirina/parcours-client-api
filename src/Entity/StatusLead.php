<?php

namespace App\Entity;

use App\Repository\StatusLeadRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=StatusLeadRepository::class)
 */
class StatusLead
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"status:lead", "post:read", "via:irm", "all:ref"})
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Customer::class, inversedBy="statusLead", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"post:read", "all:ref"})
     */
    private $customer;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"status:lead", "post:read", "via:irm", "all:ref"})
     */
    private $status;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }
}
