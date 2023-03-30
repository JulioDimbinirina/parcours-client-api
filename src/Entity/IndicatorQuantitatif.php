<?php

namespace App\Entity;

use App\Repository\IndicatorQuantitatifRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=IndicatorQuantitatifRepository::class)
 */
class IndicatorQuantitatif
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
     * @ORM\ManyToOne(targetEntity=ObjectifQuantitatif::class)
     * @Groups({"update", "update-bdc", "get-by-bdc"})
     */
    private $objectifQuantitatif;

    /**
     * @ORM\ManyToOne(targetEntity=LeadDetailOperation::class, inversedBy="indicatorQuantitatifs")
     * @Groups({"update"})
     */
    private $leadDetailOperation;

    /**
     * @ORM\ManyToOne(targetEntity=BdcOperation::class, inversedBy="indicatorQuantitatifs")
     */
    private $bdcOperation;

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

    public function getObjectifQuantitatif(): ?ObjectifQuantitatif
    {
        return $this->objectifQuantitatif;
    }

    public function setObjectifQuantitatif(?ObjectifQuantitatif $objectifQuantitatif): self
    {
        $this->objectifQuantitatif = $objectifQuantitatif;

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

    public function getBdcOperation(): ?BdcOperation
    {
        return $this->bdcOperation;
    }

    public function setBdcOperation(?BdcOperation $bdcOperation): self
    {
        $this->bdcOperation = $bdcOperation;

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
