<?php

namespace App\Entity;

use App\Repository\FournisseurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FournisseurRepository::class)
 */
class Fournisseur
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fullName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $entreprise;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $telNumber;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\OneToMany(targetEntity=BuyCarburant::class, mappedBy="fournisseur")
     */
    private $buyCarburants;

    public function __construct()
    {
        $this->buyCarburants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getEntreprise(): ?string
    {
        return $this->entreprise;
    }

    public function setEntreprise(?string $entreprise): self
    {
        $this->entreprise = $entreprise;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getTelNumber(): ?string
    {
        return $this->telNumber;
    }

    public function setTelNumber(string $telNumber): self
    {
        $this->telNumber = $telNumber;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return Collection<int, BuyCarburant>
     */
    public function getBuyCarburants(): Collection
    {
        return $this->buyCarburants;
    }

    public function addBuyCarburant(BuyCarburant $buyCarburant): self
    {
        if (!$this->buyCarburants->contains($buyCarburant)) {
            $this->buyCarburants[] = $buyCarburant;
            $buyCarburant->setFournisseur($this);
        }

        return $this;
    }

    public function removeBuyCarburant(BuyCarburant $buyCarburant): self
    {
        if ($this->buyCarburants->removeElement($buyCarburant)) {
            // set the owning side to null (unless already changed)
            if ($buyCarburant->getFournisseur() === $this) {
                $buyCarburant->setFournisseur(null);
            }
        }

        return $this;
    }
    public function  __toString()
    {
        return $this->fullName;
    }
}
