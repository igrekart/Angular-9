<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CountryRepository")
 */
class Country
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
    private $label;

    /**
     * @ORM\Column(type="integer")
     */
    private $code;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Location", mappedBy="Country")
     */
    private $locations;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Justification", mappedBy="deliveryCountry")
     */
    private $justifications;

    public function __construct()
    {
        $this->locations = new ArrayCollection();
        $this->justifications = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getCode(): ?int
    {
        return $this->code;
    }

    public function setCode(int $code): self
    {
        $this->code = $code;

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
            $location->setCountry($this);
        }

        return $this;
    }

    public function removeLocation(Location $location): self
    {
        if ($this->locations->contains($location)) {
            $this->locations->removeElement($location);
            // set the owning side to null (unless already changed)
            if ($location->getCountry() === $this) {
                $location->setCountry(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Justification[]
     */
    public function getJustifications(): Collection
    {
        return $this->justifications;
    }

    public function addJustification(Justification $justification): self
    {
        if (!$this->justifications->contains($justification)) {
            $this->justifications[] = $justification;
            $justification->setDeliveryCountry($this);
        }

        return $this;
    }

    public function removeJustification(Justification $justification): self
    {
        if ($this->justifications->contains($justification)) {
            $this->justifications->removeElement($justification);
            // set the owning side to null (unless already changed)
            if ($justification->getDeliveryCountry() === $this) {
                $justification->setDeliveryCountry(null);
            }
        }

        return $this;
    }
}
