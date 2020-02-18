<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class OOrder
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function initializeDate() {
        if (empty($this->createdAt)) {
            $this->createdAt  = new \DateTime('now');
        }
        $this->updatedAt = new \DateTime('now');
    }

    /**
     * @ORM\Column(type="string", length=255)
     * @ORM\Column(type="string", length=255)
     */
    private $reference;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $amount;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Offer", inversedBy="ordered")
     *  @ORM\JoinColumn(nullable=true)
     */
    private $offer;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Customer", inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $customer;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Payment", mappedBy="orderId")
     *  @ORM\JoinColumn(nullable=false)
     */
    private $payment;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $IDParty;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $OrderIDPCU;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $CustomerIDGAIA;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $OrderIDGAIA;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $CustomerIDBSCS;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $OrderIDBSCS;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $Step;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $Date;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Option", inversedBy="orders")
     */
    private $OptionsChoosen;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;



    public function __construct()
    {
        $this->payment = new ArrayCollection();
        $this->details = new ArrayCollection();
        $this->OptionsChoosen = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

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

    public function getOffer(): ?Offer
    {
        return $this->offer;
    }

    public function setOffer(?Offer $offer): self
    {
        $this->offer = $offer;

        return $this;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * @return Collection|Payment[]
     */
    public function getPayment(): Collection
    {
        return $this->payment;
    }

    public function addPayment(Payment $payment): self
    {
        if (!$this->payment->contains($payment)) {
            $this->payment[] = $payment;
            $payment->setOrderId($this);
        }

        return $this;
    }

    public function removePayment(Payment $payment): self
    {
        if ($this->payment->contains($payment)) {
            $this->payment->removeElement($payment);
            // set the owning side to null (unless already changed)
            if ($payment->getOrderId() === $this) {
                $payment->setOrderId(null);
            }
        }

        return $this;
    }

    public function getIDParty(): ?string
    {
        return $this->IDParty;
    }

    public function setIDParty(?string $IDParty): self
    {
        $this->IDParty = $IDParty;

        return $this;
    }

    public function getOrderIDPCU(): ?string
    {
        return $this->OrderIDPCU;
    }

    public function setOrderIDPCU(?string $OrderIDPCU): self
    {
        $this->OrderIDPCU = $OrderIDPCU;

        return $this;
    }

    public function getCustomerIDGAIA(): ?string
    {
        return $this->CustomerIDGAIA;
    }

    public function setCustomerIDGAIA(?string $CustomerIDGAIA): self
    {
        $this->CustomerIDGAIA = $CustomerIDGAIA;

        return $this;
    }

    public function getOrderIDGAIA(): ?string
    {
        return $this->OrderIDGAIA;
    }

    public function setOrderIDGAIA(?string $OrderIDGAIA): self
    {
        $this->OrderIDGAIA = $OrderIDGAIA;

        return $this;
    }

    public function getCustomerIDBSCS(): ?string
    {
        return $this->CustomerIDBSCS;
    }

    public function setCustomerIDBSCS(?string $CustomerIDBSCS): self
    {
        $this->CustomerIDBSCS = $CustomerIDBSCS;

        return $this;
    }

    public function getOrderIDBSCS(): ?string
    {
        return $this->OrderIDBSCS;
    }

    public function setOrderIDBSCS(?string $OrderIDBSCS): self
    {
        $this->OrderIDBSCS = $OrderIDBSCS;

        return $this;
    }

    public function getStep(): ?int
    {
        return $this->Step;
    }

    public function setStep(?int $Step): self
    {
        $this->Step = $Step;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->Date;
    }

    public function setDate(?\DateTimeInterface $Date): self
    {
        $this->Date = $Date;

        return $this;
    }

    /**
     * @return Collection|Option[]
     */
    public function getOptionsChoosen(): Collection
    {
        return $this->OptionsChoosen;
    }

    public function addOptionsChoosen(Option $optionsChoosen): self
    {
        if (!$this->OptionsChoosen->contains($optionsChoosen)) {
            $this->OptionsChoosen[] = $optionsChoosen;
        }

        return $this;
    }

    public function removeOptionsChoosen(Option $optionsChoosen): self
    {
        if ($this->OptionsChoosen->contains($optionsChoosen)) {
            $this->OptionsChoosen->removeElement($optionsChoosen);
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
