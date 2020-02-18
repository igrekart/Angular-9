<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;


class Process
{

    //internal logic

    private $step;
    /**
     * @return mixed
     */
    public function getStep()
    {
        return $this->step;
    }

    /**
     * @param mixed $step
     */
    public function setStep($step): void
    {
        $this->step = $step;
    }
//    CUSTOMER

    private  $civility;

    private $lastname;

    private $firstname;

    private $birth;

    private $birthPlace;

    private $nationality;


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


    // LOCALISATION


    private  $location;

    private $country;

    private $city;

    private $longitude;

    private $latitude;

    private $town;

    private $district;

    private $addition;

    private  $eligible;

    /**
     * @return mixed
     */

    public function getCountry()
    {
        return $this->country;
    }

    public function setCountry($country): self
    {
        if (is_null($this->country)) {
            $this->country = $country;
        } else {
            $this->justificationCountry = $country;
        }
        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getTown(): ?string
    {
        return $this->town;
    }

    public function setTown(string $town): self
    {
        $this->town = $town;

        return $this;
    }

    public function getDistrict(): ?string
    {
        return $this->district;
    }

    public function setDistrict(string $district): self
    {
        $this->district = $district;

        return $this;
    }

    public function getAddition(): ?string
    {
        return $this->addition;
    }

    public function setAddition(?string $addition): self
    {
        $this->addition = $addition;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param mixed $location
     */
    public function setLocation($location): void
    {
        $this->location = $location;
    }

    /**
     * @return mixed
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @return mixed
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param mixed $latitude
     */
    public function setLatitude($latitude): void
    {
        $this->latitude = $latitude;
    }

    /**
     * @param mixed $longitude
     */
    public function setLongitude($longitude): void
    {
        $this->longitude = $longitude;
    }

    /**
     * @return mixed
     */
    public function getEligible()
    {
        return $this->eligible;
    }

    /**
     * @param mixed $eligible
     */
    public function setEligible($eligible): void
    {
        $this->eligible = $eligible;
    }



    // Justification

    private $type;

    private $identifier;

    private $emission;

    private $expiration;

    private  $identity;

    private $authority;

    private $deliveryCountry;

    // Voir setCountry and getCountry
    private $justificationCountry;

    private $file;

    private $customer;

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @param mixed $identifier
     */
    public function setIdentifier($identifier): void
    {
        $this->identifier = $identifier;
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
    public function getFile():? array
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     */
    public function setFile(array $file): void
    {
        $this->file = $file;
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


    //tests

    private $offer;

    /**
     * @return mixed
     */
    public function getOffer()
    {
        return $this->offer;
    }

    /**
     * @param mixed $offer
     */
    public function setOffer($offer): void
    {
        $this->offer = $offer;
    }


    private $options;

    /**
     * @return mixed
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param mixed $options
     */
    public function setOptions($options): void
    {
        $this->options = $options;
    }

    /**
     * @return mixed
     */
    public function getCivility()
    {
        return $this->civility;
    }

    /**
     * @param mixed $civility
     */
    public function setCivility($civility): void
    {
        $this->civility = $civility;
    }


    //payment

    private $paymentChoice;

    private $amount;

    private $check;

    private $mobileMoney;

    public function getPaymentChoice():? PaymentChoice
    {
        return $this->paymentChoice;
    }
    
    public function setPaymentChoice(PaymentChoice $paymentChoice)
    {
        $this->paymentChoice = $paymentChoice;
    }

    public function getAmount() 
    {
        return $this->amount;
    }
    
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    public function getCheck() 
    {
        return $this->check;
    }
    
    public function setCheck($check)
    {
        $this->check = $check;
    }

    /**
     * @return mixed
     */
    public function getMobileMoney()
    {
        return $this->mobileMoney;
    }

    /**
     * @param mixed $mobileMoney
     */
    public function setMobileMoney($mobileMoney): void
    {
        $this->mobileMoney = $mobileMoney;
    }

    /**
     * @return mixed
     */
    public function getIdentity()
    {
        return $this->identity;
    }

    /**
     * @param mixed $identity
     */
    public function setIdentity($identity): void
    {
        $this->identity = $identity;
    }

    /**
     * @return mixed
     */
    public function getAuthority()
    {
        return $this->authority;
    }

    /**
     * @param mixed $authority
     */
    public function setAuthority($authority): void
    {
        $this->authority = $authority;
    }

    /**
     * @return mixed
     */
    public function getDeliveryCountry()
    {
        return $this->deliveryCountry;
    }

    /**
     * @param mixed $deliveryCountry
     */
    public function setDeliveryCountry($deliveryCountry): void
    {
        $this->deliveryCountry = $deliveryCountry;
    }



}
