<?php

namespace App\Entity;

use App\Repository\SellCarburantRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SellCarburantRepository::class)
 */
class SellCarburant
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $litre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $typeCarburant;

    /**
     * @ORM\Column(type="float")
     */
    private $prix;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $agent;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $clientVehicule;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $client;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $buyAt;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLitre(): ?float
    {
        return $this->litre;
    }

    public function setLitre(?float $litre): self
    {
        $this->litre = $litre;

        return $this;
    }

    public function getTypeCarburant(): ?string
    {
        return $this->typeCarburant;
    }

    public function setTypeCarburant(string $typeCarburant): self
    {
        $this->typeCarburant = $typeCarburant;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getAgent(): ?string
    {
        return $this->agent;
    }

    public function setAgent(string $agent): self
    {
        $this->agent = $agent;

        return $this;
    }

    public function getClientVehicule(): ?string
    {
        return $this->clientVehicule;
    }

    public function setClientVehicule(?string $clientVehicule): self
    {
        $this->clientVehicule = $clientVehicule;

        return $this;
    }

    public function getClient(): ?string
    {
        return $this->client;
    }

    public function setClient(?string $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getBuyAt(): ?\DateTimeImmutable
    {
        return $this->buyAt;
    }

    public function setBuyAt(\DateTimeImmutable $buyAt): self
    {
        $this->buyAt = $buyAt;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
