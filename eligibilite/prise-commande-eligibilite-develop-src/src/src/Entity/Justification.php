<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\JustificationRepository")
 */
class Justification
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $identifier;

    /**
     * @ORM\Column(type="date")
     */
    private $emission;

    /**
     * @ORM\Column(type="date")
     */
    private $expiration;



    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Customer", inversedBy="justification")
     * @ORM\JoinColumn(nullable=false)
     */
    private $customer;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Identity", inversedBy="Justification")
     */
    private $identity;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Authority", inversedBy="Justification")
     */
    private $authority;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Country", inversedBy="justifications")
     * @ORM\JoinColumn(nullable=false)
     */
    private $deliveryCountry;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Image", mappedBy="Justification")
     */
    private $images;

    public function __construct()
    {
        $this->images = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }


    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    public function setIdentifier(string $identifier): self
    {
        $this->identifier = $identifier;

        return $this;
    }

    public function getEmission(): ?\DateTimeInterface
    {
        return $this->emission;
    }

    public function setEmission(\DateTimeInterface $emission): self
    {
        $this->emission = $emission;

        return $this;
    }

    public function getExpiration(): ?\DateTimeInterface
    {
        return $this->expiration;
    }

    public function setExpiration(\DateTimeInterface $expiration): self
    {
        $this->expiration = $expiration;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    /**
     * @param mixed $customer
     */
    public function setCustomer(Customer $customer): void
    {
        $this->customer = $customer;
    }

    public function getIdentity(): ?Identity
    {
        return $this->identity;
    }

    public function setIdentity(?Identity $identity): self
    {
        $this->identity = $identity;

        return $this;
    }

    public function getAuthority(): ?Authority
    {
        return $this->authority;
    }

    public function setAuthority(?Authority $authority): self
    {
        $this->authority = $authority;

        return $this;
    }

    public function getDeliveryCountry(): ?Country
    {
        return $this->deliveryCountry;
    }

    public function setDeliveryCountry(?Country $deliveryCountry): self
    {
        $this->deliveryCountry = $deliveryCountry;

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setJustification($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getJustification() === $this) {
                $image->setJustification(null);
            }
        }

        return $this;
    }
}
