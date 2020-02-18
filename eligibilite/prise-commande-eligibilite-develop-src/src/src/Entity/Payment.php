<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PaymentRepository")
 */
class Payment
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
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PaymentChoice", inversedBy="payment")
     * @ORM\JoinColumn(nullable=false)
     */
    private $paymentChoice;

    /**
     * @ORM\ManyToOne(targetEntity="OOrder", inversedBy="payment")
     */
    private $orderId;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getPaymentChoice(): ?PaymentChoice
    {
        return $this->paymentChoice;
    }

    public function setPaymentChoice(?PaymentChoice $paymentChoice): self
    {
        $this->paymentChoice = $paymentChoice;

        return $this;
    }

    public function getOrderId(): ?OOrder
    {
        return $this->orderId;
    }

    public function setOrderId(?OOrder $orderId): self
    {
        $this->orderId = $orderId;

        return $this;
    }
}
