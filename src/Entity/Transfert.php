<?php

namespace App\Entity;

use App\Repository\TransfertRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TransfertRepository::class)
 */
class Transfert
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="transferts")
     */
    private $client;

    /**
     * @ORM\Column(type="float")
     */
    private $montant;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $frais;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $destinataire;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $tel;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $agency;

    /**
     * @ORM\ManyToOne(targetEntity=Agency::class, inversedBy="transferts")
     */
    private $transagency;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $agent;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $transagent;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $facture;

    /**
     * @ORM\Column(type="bigint")
     */
    private $secretid;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $sentAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $receveAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $destinateur;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $telsender;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $paid;

   

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClient(): ?User
    {
        return $this->client;
    }

    public function setClient(?User $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getFrais(): ?float
    {
        return $this->frais;
    }

    public function setFrais(?float $frais): self
    {
        $this->frais = $frais;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(string $tel): self
    {
        $this->tel = $tel;

        return $this;
    }

    public function getAgency(): ?string
    {
        return $this->agency;
    }

    public function setAgency(string $agency): self
    {
        $this->agency = $agency;

        return $this;
    }

    public function getTransagency(): ?Agency
    {
        return $this->transagency;
    }

    public function setTransagency(?Agency $transagency): self
    {
        $this->transagency = $transagency;

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

    public function getTransagent(): ?string
    {
        return $this->transagent;
    }

    public function setTransagent(string $transagent): self
    {
        $this->transagent = $transagent;

        return $this;
    }

    public function getFacture(): ?string
    {
        return $this->facture;
    }

    public function setFacture(?string $facture): self
    {
        $this->facture = $facture;

        return $this;
    }

    public function getDestinataire(): ?string
    {
        return $this->destinataire;
    }

    public function setDestinataire(string $destinataire): self
    {
        $this->destinataire = $destinataire;

        return $this;
    }


    public function getSecretid(): ?int
    {
        return $this->secretid;
    }

    public function setSecretid(int $secretid): self
    {
        $this->secretid = $secretid;

        return $this;
    }

    public function getSentAt(): ?\DateTimeImmutable
    {
        return $this->sentAt;
    }

    public function setSentAt(\DateTimeImmutable $sentAt): self
    {
        $this->sentAt = $sentAt;

        return $this;
    }

    public function getReceveAt(): ?\DateTimeImmutable
    {
        return $this->receveAt;
    }

    public function setReceveAt(?\DateTimeImmutable $receveAt): self
    {
        $this->receveAt = $receveAt;

        return $this;
    }

    public function __toString()
    {
        return $this->getSecretid();
    }

    public function getDestinateur(): ?string
    {
        return $this->destinateur;
    }

    public function setDestinateur(?string $destinateur): self
    {
        $this->destinateur = $destinateur;

        return $this;
    }

    public function getTelsender(): ?string
    {
        return $this->telsender;
    }

    public function setTelsender(?string $telsender): self
    {
        $this->telsender = $telsender;

        return $this;
    }

    public function getPaid(): ?string
    {
        return $this->paid;
    }

    public function setPaid(?string $paid): self
    {
        $this->paid = $paid;

        return $this;
    }
}
