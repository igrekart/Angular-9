<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CustomerRepository")
 */
class Customer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *  @Groups ("Customer::setting")
     */
    private $id;

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
     * @ORM\Column(type="date")
     *  @Groups ("Customer::setting")
     */
    private $birth;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Groups ("Customer::setting")
     */
    private $birthPlace;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Groups ("Customer::setting")
     */
    private $nationality;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Location", mappedBy="customer")
     *  @Groups ("Customer::setting")
     */
    private $locations;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Interest", mappedBy="customer")
     *  @Groups ("Customer::setting")
     */
    private $interest;

    /**
     * @ORM\OneToMany(targetEntity="OOrder", mappedBy="customer")
     *  @Groups ("Customer::setting")
     */
    private $orders;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Justification", mappedBy="customer")
     *  @Groups ("Customer::setting")
     */
    private $justification;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="customers")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Civility", inversedBy="customers")
     */
    private $Civility;


    public function __construct()
    {
        $this->locations = new ArrayCollection();
        $this->interest = new ArrayCollection();
        $this->orders = new ArrayCollection();
        $this->justification = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getBirth(): ?\DateTimeInterface
    {
        return $this->birth;
    }

    public function setBirth(\DateTimeInterface $birth): self
    {
        $this->birth = $birth;

        return $this;
    }

    public function getBirthPlace(): ?string
    {
        return $this->birthPlace;
    }

    public function setBirthPlace(string $birthPlace): self
    {
        $this->birthPlace = $birthPlace;

        return $this;
    }

    public function getNationality(): ?string
    {
        return $this->nationality;
    }

    public function setNationality(string $nationality): self
    {
        $this->nationality = $nationality;

        return $this;
    }

    /**
     * @return Collection|Location[]
     */
    public function getLocations(): Collection
    {
        return $this->locations;
    }

    public function addLocation(Location $location): self
    {
        if (!$this->locations->contains($location)) {
            $this->locations[] = $location;
            $location->setCustomer($this);
        }

        return $this;
    }

    public function removeLocation(Location $location): self
    {
        if ($this->locations->contains($location)) {
            $this->locations->removeElement($location);
            // set the owning side to null (unless already changed)
            if ($location->getCustomer() === $this) {
                $location->setCustomer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Interest[]
     */
    public function getInterest(): Collection
    {
        return $this->interest;
    }

    public function addInterest(Interest $interest): self
    {
        if (!$this->interest->contains($interest)) {
            $this->interest[] = $interest;
            $interest->setCustomer($this);
        }

        return $this;
    }

    public function removeInterest(Interest $interest): self
    {
        if ($this->interest->contains($interest)) {
            $this->interest->removeElement($interest);
            // set the owning side to null (unless already changed)
            if ($interest->getCustomer() === $this) {
                $interest->setCustomer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|OOrder[]
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(OOrder $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setCustomer($this);
        }

        return $this;
    }

    public function removeOrder(OOrder $order): self
    {
        if ($this->orders->contains($order)) {
            $this->orders->removeElement($order);
            // set the owning side to null (unless already changed)
            if ($order->getCustomer() === $this) {
                $order->setCustomer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Justification[]
     */
    public function getJustification(): Collection
    {
        return $this->justification;
    }

    public function addJustification(Justification $justification): self
    {
        if (!$this->justification->contains($justification)) {
            $this->justification[] = $justification;
            $justification->setCustomer($this);
        }

        return $this;
    }

    public function removeJustification(Justification $justification): self
    {
        if ($this->justification->contains($justification)) {
            $this->justification->removeElement($justification);
            // set the owning side to null (unless already changed)
            if ($justification->getCustomer() === $this) {
                $justification->setCustomer(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCivility(): ?Civility
    {
        return $this->Civility;
    }

    public function setCivility(?Civility $Civility): self
    {
        $this->Civility = $Civility;

        return $this;
    }
}
