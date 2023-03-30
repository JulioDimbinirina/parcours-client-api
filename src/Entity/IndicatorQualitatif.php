<?php

namespace App\Entity;

use App\Repository\IndicatorQualitatifRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=IndicatorQualitatifRepository::class)
 */
class IndicatorQualitatif
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"update", "update-bdc", "get-by-bdc"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"update", "update-bdc", "get-by-bdc"})
     */
    private $indicator;

    /**
     * @ORM\ManyToOne(targetEntity=ObjectifQualitatif::class)
     * @Groups({"update", "update-bdc", "get-by-bdc"})
     */
    private $objectifQualitatif;

    /**
     * @ORM\ManyToOne(targetEntity=BdcOperation::class, inversedBy="indicatorQualitatifs")
     */
    private $bdcOperation;

    /**
     * @ORM\ManyToOne(targetEntity=LeadDetailOperation::class, inversedBy="indicatorQualitatifs")
     */
    private $leadDetailOperation;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $uniqBdcFqOperation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIndicator(): ?string
    {
        return $this->indicator;
    }

    public function setIndicator(?string $indicator): self
    {
        $this->indicator = $indicator;

        return $this;
    }

    public function getObjectifQualitatif(): ?ObjectifQualitatif
    {
        return $this->objectifQualitatif;
    }

    public function setObjectifQualitatif(?ObjectifQualitatif $objectifQualitatif): self
    {
        $this->objectifQualitatif = $objectifQualitatif;

        return $this;
    }

    public function getBdcOperation(): ?BdcOperation
    {
        return $this->bdcOperation;
    }

    public function setBdcOperation(?BdcOperation $bdcOperation): self
    {
        $this->bdcOperation = $bdcOperation;

        return $this;
    }

    public function getLeadDetailOperation(): ?LeadDetailOperation
    {
        return $this->leadDetailOperation;
    }

    public function setLeadDetailOperation(?LeadDetailOperation $leadDetailOperation): self
    {
        $this->leadDetailOperation = $leadDetailOperation;

        return $this;
    }

    public function getUniqBdcFqOperation(): ?string
    {
        return $this->uniqBdcFqOperation;
    }

    public function setUniqBdcFqOperation(?string $uniqBdcFqOperation): self
    {
        $this->uniqBdcFqOperation = $uniqBdcFqOperation;

        return $this;
    }
}
