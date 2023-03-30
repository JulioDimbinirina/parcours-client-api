<?php

namespace App\Entity;

use App\Repository\SuiteProcessRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=SuiteProcessRepository::class)
 */
class SuiteProcess
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"get-by-bdc", "update-bdc"})
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Bdc::class, inversedBy="suiteProcess", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $bdc;

    /**
     * @ORM\Column(type="smallint")
     * @Groups({"get-by-bdc", "update-bdc"})
     */
    private $isCustomerWillSendBdc;

    /**
     * @ORM\Column(type="smallint")
     * @Groups({"get-by-bdc", "update-bdc"})
     */
    private $isSeizureContract;

    /**
     * @ORM\Column(type="smallint")
     * @Groups({"get-by-bdc", "update-bdc"})
     */
    private $isDevisPassToProdAfterSign;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBdc(): ?Bdc
    {
        return $this->bdc;
    }

    public function setBdc(Bdc $bdc): self
    {
        $this->bdc = $bdc;

        return $this;
    }

    public function getIsCustomerWillSendBdc(): ?int
    {
        return $this->isCustomerWillSendBdc;
    }

    public function setIsCustomerWillSendBdc(int $isCustomerWillSendBdc): self
    {
        $this->isCustomerWillSendBdc = $isCustomerWillSendBdc;

        return $this;
    }

    public function getIsSeizureContract(): ?int
    {
        return $this->isSeizureContract;
    }

    public function setIsSeizureContract(int $isSeizureContract): self
    {
        $this->isSeizureContract = $isSeizureContract;

        return $this;
    }

    public function getIsDevisPassToProdAfterSign(): ?int
    {
        return $this->isDevisPassToProdAfterSign;
    }

    public function setIsDevisPassToProdAfterSign(int $isDevisPassToProdAfterSign): self
    {
        $this->isDevisPassToProdAfterSign = $isDevisPassToProdAfterSign;

        return $this;
    }
}
