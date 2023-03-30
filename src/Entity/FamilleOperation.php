<?php

namespace App\Entity;

use App\Repository\FamilleOperationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=FamilleOperationRepository::class)
 */
class FamilleOperation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"get-by-bdc", "get-fq-id", "ref", "bdc-operation", "post:read", "import-excel"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"get-by-bdc", "get-fq-id", "ref", "bdc-operation", "post:read", "import-excel"})
     */
    private $libelle;

	/**
	* @ORM\Column(type="integer", nullable=true)
     * @Groups({"import-excel"})
	*/
	private $isIrm;

	/**
	* @ORM\Column(type="integer", nullable=true)
     * @Groups({"import-excel"})
	*/
	private $isSiRenta;

	/**
	* @ORM\Column(type="integer", nullable=true)
     * @Groups({"import-excel"})
	*/
	private $isSage;

    /**
     * @ORM\OneToMany(targetEntity=Operation::class, mappedBy="familleOperation", orphanRemoval=true)
     */
    private $operations;

    /**
     * @ORM\OneToMany(targetEntity=LeadDetailOperation::class, mappedBy="familleOperation", orphanRemoval=true)
     */
    private $leadDetailOperations;

    /**
     * @ORM\OneToMany(targetEntity=BdcOperation::class, mappedBy="familleOperation")
     */
    private $bdcOperations;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Groups({"get-by-bdc", "import-excel"})
     */
    private $codeFamille;

    public function __construct()
    {
        $this->operations = new ArrayCollection();
        $this->bdcOperations = new ArrayCollection();
        $this->leadDetailOperations = new ArrayCollection();
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
     * @return Collection|Operation[]
     */
    public function getOperations(): Collection
    {
        return $this->operations;
    }

    public function addOperation(Operation $operation): self
    {
        if (!$this->operations->contains($operation)) {
            $this->operations[] = $operation;
            $operation->setFamilleOperation($this);
        }

        return $this;
    }

    /**
     * @return Collection|LeadDetailOperation[]
     */
    public function getLeadDetailOperations(): Collection
    {
        return $this->leadDetailOperations;
    }

    public function addLeadDetailOperation(LeadDetailOperation $leadDetailOperation): self
    {
        if (!$this->leadDetailOperations->contains($leadDetailOperation)) {
            $this->leadDetailOperations[] = $leadDetailOperation;
            $leadDetailOperation->setFamilleOperation($this);
        }

        return $this;
    }

    public function removeOperation(Operation $operation): self
    {
        if ($this->operations->removeElement($operation)) {
            // set the owning side to null (unless already changed)
            if ($operation->getFamilleOperation() === $this) {
                $operation->setFamilleOperation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|BdcOperation[]
     */
    public function getBdcOperations(): Collection
    {
        return $this->bdcOperations;
    }

    public function addBdcOperation(BdcOperation $bdcOperation): self
    {
        if (!$this->bdcOperations->contains($bdcOperation)) {
            $this->bdcOperations[] = $bdcOperation;
            $bdcOperation->setFamilleOperation($this);
        }

        return $this;
    }

    public function removeBdcOperation(BdcOperation $bdcOperation): self
    {
        if ($this->bdcOperations->removeElement($bdcOperation)) {
            // set the owning side to null (unless already changed)
            if ($bdcOperation->getFamilleOperation() === $this) {
                $bdcOperation->setFamilleOperation(null);
            }
        }

        return $this;
    }

	public function getIsIrm(): ?int
                   
               	{
                       
               		return $this->isIrm;
                   
               	}
	

    
	public function setIsIrm(?int $isIrm): self
                   
               	{
                       
               		$this->isIrm = $isIrm;
               
                       return $this;
                   
               	}

    

	public function getIsSiRenta(): ?int
                   
               	{
                       
               		return $this->isSiRenta;
                   
               	}

   
		
	public function setIsSiRenta(?int $isSiRenta): self
                   
               	{
                       
               		$this->isSiRenta = $isSiRenta;
               
                       
               		return $this;
                   
               	}

  
			
	public function getIsSage(): ?int
                   
               	{
                       
               		return $this->isSage;
                   
               	}

    

	public function setIsSage(?int $isSage): self
                   
               	{
                       
               		$this->isSage = $isSage;
               
                       
               		return $this;
                   
               	}

    public function removeLeadDetailOperation(LeadDetailOperation $leadDetailOperation): self
    {
        if ($this->leadDetailOperations->removeElement($leadDetailOperation)) {
            // set the owning side to null (unless already changed)
            if ($leadDetailOperation->getFamilleOperation() === $this) {
                $leadDetailOperation->setFamilleOperation(null);
            }
        }

        return $this;
    }

    public function getCodeFamille(): ?string
    {
        return $this->codeFamille;
    }

    public function setCodeFamille(?string $codeFamille): self
    {
        $this->codeFamille = $codeFamille;

        return $this;
    }
}
