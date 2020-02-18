<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OfferFeaturesRepository")
 */
class OfferFeatures
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
     * @ORM\ManyToMany(targetEntity="App\Entity\Offer", inversedBy="offerFeatures")
     */
    private $Offer;

    public function __construct()
    {
        $this->Offer = new ArrayCollection();
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

    /**
     * @return Collection|Offer[]
     */
    public function getOffer(): Collection
    {
        return $this->Offer;
    }

    public function addOffer(Offer $offer): self
    {
        if (!$this->Offer->contains($offer)) {
            $this->Offer[] = $offer;
        }

        return $this;
    }

    public function removeOffer(Offer $offer): self
    {
        if ($this->Offer->contains($offer)) {
            $this->Offer->removeElement($offer);
        }

        return $this;
    }
}
