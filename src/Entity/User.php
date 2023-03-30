<?php


namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"bdcs", "current-user", "fiche-client", "status:lead"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"bdcs", "current-user", "fiche-client"})
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="json")
     * @Groups({"current-user", "bdcs"})
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string", length=180)
     * @Groups({"current-user", "fiche-client"})
     */
    private $username;

    /**
     * @ORM\OneToMany(targetEntity=Customer::class, mappedBy="user", orphanRemoval=true)
     */
    private $customers;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="user")
     * @ORM\JoinColumn(nullable=false)
     */
    private $parent;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $tel;

    /**
     * @ORM\ManyToOne(targetEntity=PaysProduction::class, inversedBy="users")
     * @Groups({"current-user", "update-bdc", "bdcs", "fiche-client"})
     */
    private $paysProduction;

    public function __construct()
    {
        $this->customers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @see UserInterface
     * @return string|null
     */
    public function getCurrentUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        return sprintf('%d %s', $this->id, $this->username);
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        return null;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return Collection|Customer[]
     */
    public function getCustomers(): Collection
    {
        return $this->customers;
    }

    public function addCustomer(Customer $customer): self
    {
        if (!$this->customers->contains($customer)) {
            $this->customers[] = $customer;
            $customer->setUser($this);
        }

        return $this;
    }

    public function removeCustomer(Customer $customer): self
    {
        if ($this->customers->removeElement($customer)) {
            // set the owning side to null (unless already changed)
            if ($customer->getUser() === $this) {
                $customer->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return self
     */
    public function getParent(): self
    {
        return $this->parent;
    }

    public function setParent(User $user): self
    {
        $this->parent = $user;
        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(?string $tel): self
    {
        $this->tel = $tel;

        return $this;
    }

    public function getPaysProduction(): ?PaysProduction
    {
        return $this->paysProduction;
    }

    public function setPaysProduction(?PaysProduction $paysProduction): self
    {
        $this->paysProduction = $paysProduction;

        return $this;
    }
}
