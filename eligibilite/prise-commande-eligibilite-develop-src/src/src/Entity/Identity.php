<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\IdentityRepository")
 */
class Identity
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
     * @ORM\OneToMany(targetEntity="App\Entity\Justification", mappedBy="identity")
     */
    private $Justification;

    public function __construct()
    {
        $this->Justification = new ArrayCollection();
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
     * @return Collection|Justification[]
     */
    public function getJustification(): Collection
    {
        return $this->Justification;
    }

    public function addJustification(Justification $justification): self
    {
        if (!$this->Justification->contains($justification)) {
            $this->Justification[] = $justification;
            $justification->setIdentity($this);
        }

        return $this;
    }

    public function removeJustification(Justification $justification): self
    {
        if ($this->Justification->contains($justification)) {
            $this->Justification->removeElement($justification);
            // set the owning side to null (unless already changed)
            if ($justification->getIdentity() === $this) {
                $justification->setIdentity(null);
            }
        }

        return $this;
    }
}
