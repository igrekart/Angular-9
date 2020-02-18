<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BankRepository")
 */
class Bank
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
     * @ORM\OneToMany(targetEntity="App\Entity\BankCheck", mappedBy="bank")
     */
    private $BankCheck;

    public function __construct()
    {
        $this->BankCheck = new ArrayCollection();
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
     * @return Collection|BankCheck[]
     */
    public function getBankCheck(): Collection
    {
        return $this->BankCheck;
    }

    public function addBankCheck(BankCheck $bankCheck): self
    {
        if (!$this->BankCheck->contains($bankCheck)) {
            $this->BankCheck[] = $bankCheck;
            $bankCheck->setBank($this);
        }

        return $this;
    }

    public function removeBankCheck(BankCheck $bankCheck): self
    {
        if ($this->BankCheck->contains($bankCheck)) {
            $this->BankCheck->removeElement($bankCheck);
            // set the owning side to null (unless already changed)
            if ($bankCheck->getBank() === $this) {
                $bankCheck->setBank(null);
            }
        }

        return $this;
    }
}
