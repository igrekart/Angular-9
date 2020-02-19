<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UsersRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups ("Customer::setting")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Groups ("Customer::setting")
     */
    private $login;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Groups ("Customer::setting")
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Groups ("Customer::setting")
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Groups ("Customer::setting")
     */
    private $mail;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Groups ("Customer::setting")
     */
    private $phone;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Customer", mappedBy="user")
     *  @Groups ("Customer::setting")
     */
    private $customers;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\UserRole", inversedBy="users")
     *  @Groups ("Customer::setting")
     */
    private $userRoles;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Civility", inversedBy="users")
     *  @Groups ("Customer::setting")
     */
    private $civility;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Groups ("Customer::setting")
     */
    private $password;


    public function __construct()
    {
        $this->customers = new ArrayCollection();
        $this->userRoles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

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
        if ($this->customers->contains($customer)) {
            $this->customers->removeElement($customer);
            // set the owning side to null (unless already changed)
            if ($customer->getUser() === $this) {
                $customer->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        $roles = [];

        $userRoles = $this->getUserRoles()->toArray();
        foreach ($userRoles as $role) {
            $roles[]= $role->getLabel();
        }
        return $roles;
    }

    /**
     * @inheritDoc
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    /**
     * @inheritDoc
     */
    public function getUsername()
    {
        return $this->login;
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * @return Collection|UserRole[]
     */
    public function getUserRoles(): Collection
    {
        return $this->userRoles;
    }

    public function addUserRole(UserRole $userRole): self
    {
        if (!$this->userRoles->contains($userRole)) {
            $this->userRoles[] = $userRole;
        }

        return $this;
    }

    public function removeUserRole(UserRole $userRole): self
    {
        if ($this->userRoles->contains($userRole)) {
            $this->userRoles->removeElement($userRole);
        }

        return $this;
    }

    public function getCivility(): ?Civility
    {
        return $this->civility;
    }

    public function setCivility(?Civility $civility): self
    {
        $this->civility = $civility;

        return $this;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

}
