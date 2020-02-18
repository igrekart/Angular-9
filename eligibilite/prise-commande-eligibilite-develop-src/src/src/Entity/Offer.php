<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OfferRepository")
 */
class Offer
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
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="OOrder", mappedBy="offer")
     */
    private $ordered;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\OfferFeatures", mappedBy="Offer")
     */
    private $offerFeatures;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Option", mappedBy="Offer")
     */
    private $options;


    public function __construct()
    {
        $this->ordered = new ArrayCollection();
        $this->offerFeatures = new ArrayCollection();
        $this->options = new ArrayCollection();

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

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return Collection|OOrder[]
     */
    public function getOrdered(): Collection
    {
        return $this->ordered;
    }

    public function addOrdered(OOrder $ordered): self
    {
        if (!$this->ordered->contains($ordered)) {
            $this->ordered[] = $ordered;
            $ordered->setOffer($this);
        }

        return $this;
    }

    public function removeOrdered(OOrder $ordered): self
    {
        if ($this->ordered->contains($ordered)) {
            $this->ordered->removeElement($ordered);
            // set the owning side to null (unless already changed)
            if ($ordered->getOffer() === $this) {
                $ordered->setOffer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|OfferFeatures[]
     */
    public function getOfferFeatures(): Collection
    {
        return $this->offerFeatures;
    }

    public function addOfferFeature(OfferFeatures $offerFeature): self
    {
        if (!$this->offerFeatures->contains($offerFeature)) {
            $this->offerFeatures[] = $offerFeature;
            $offerFeature->addOffer($this);
        }

        return $this;
    }

    public function removeOfferFeature(OfferFeatures $offerFeature): self
    {
        if ($this->offerFeatures->contains($offerFeature)) {
            $this->offerFeatures->removeElement($offerFeature);
            $offerFeature->removeOffer($this);
        }

        return $this;
    }

    /**
     * @return Collection|Option[]
     */
    public function getOptions(): Collection
    {
        return $this->options;
    }

    public function addOption(Option $option): self
    {
        if (!$this->options->contains($option)) {
            $this->options[] = $option;
            $option->addOffer($this);
        }

        return $this;
    }

    public function removeOption(Option $option): self
    {
        if ($this->options->contains($option)) {
            $this->options->removeElement($option);
            $option->removeOffer($this);
        }

        return $this;
    }
}
