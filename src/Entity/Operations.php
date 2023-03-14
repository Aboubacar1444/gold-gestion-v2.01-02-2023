<?php

namespace App\Entity;

use App\Repository\OperationsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OperationsRepository::class)
 */
class Operations
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
    private $type;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $base;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $poideau;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $poidair;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $densite;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $karat;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $prixu;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $montant;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $avance;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $totalm;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $total;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="operations")
     */
    private $client;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $agent;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $facture;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $numero;

    /**
     * @ORM\Column(type="string", length=4000, nullable=true)
     */
    private $motif;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tempclient;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $time;

    /**
     * @ORM\ManyToOne(targetEntity=Agency::class, inversedBy="operations")
     */
    private $agency;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $tel;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $qte;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $product;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $taux;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $avdollar;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $valid;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getBase(): ?float
    {
        return $this->base;
    }

    public function setBase(float $base): self
    {
        $this->base = $base;

        return $this;
    }

    public function getPoideau(): ?float
    {
        return $this->poideau;
    }

    public function setPoideau(float $poideau): self
    {
        $this->poideau = $poideau;

        return $this;
    }

    public function getPoidair(): ?float
    {
        return $this->poidair;
    }

    public function setPoidair(float $poidair): self
    {
        $this->poidair = $poidair;

        return $this;
    }

    public function getDensite(): ?float
    {
        return $this->densite;
    }

    public function setDensite(float $densite): self
    {
        $this->densite = $densite;

        return $this;
    }

    public function getKarat(): ?float
    {
        return $this->karat;
    }

    public function setKarat(float $karat): self
    {
        $this->karat = $karat;

        return $this;
    }

    public function getPrixu(): ?float
    {
        return $this->prixu;
    }

    public function setPrixu(float $prixu): self
    {
        $this->prixu = $prixu;

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

    public function getAvance(): ?float
    {
        return $this->avance;
    }

    public function setAvance(float $avance): self
    {
        $this->avance = $avance;

        return $this;
    }

    public function getTotalm(): ?float
    {
        return $this->totalm;
    }

    public function setTotalm(float $totalm): self
    {
        $this->totalm = $totalm;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;

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

    public function getClient(): ?User
    {
        return $this->client;
    }

    public function setClient(?User $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getAgent(): ?string
    {
        return $this->agent;
    }

    public function setAgent(?string $agent): self
    {
        $this->agent = $agent;

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

    public function getNumero(): ?int
    {
        return $this->numero;
    }

    public function setNumero(?int $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getMotif(): ?string
    {
        return $this->motif;
    }

    public function setMotif(?string $motif): self
    {
        $this->motif = $motif;

        return $this;
    }

    public function getTempclient(): ?string
    {
        return $this->tempclient;
    }

    public function setTempclient(?string $tempclient): self
    {
        $this->tempclient = $tempclient;

        return $this;
    }

    public function getTime(): ?string
    {
        return $this->time;
    }

    public function setTime(string $time): self
    {
        $this->time = $time;

        return $this;
    }

    public function getAgency(): ?Agency
    {
        return $this->agency;
    }

    public function setAgency(?Agency $agency): self
    {
        $this->agency = $agency;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(?string $tel): self
    {
        $this->tel = $tel;

        return $this;
    }

    public function getQte(): ?float
    {
        return $this->qte;
    }

    public function setQte(?float $qte): self
    {
        $this->qte = $qte;

        return $this;
    }

    public function getProduct(): ?string
    {
        return $this->product;
    }

    public function setProduct(?string $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getTaux(): ?float
    {
        return $this->taux;
    }

    public function setTaux(?float $taux): self
    {
        $this->taux = $taux;

        return $this;
    }

    public function getAvdollar(): ?float
    {
        return $this->avdollar;
    }

    public function setAvdollar(?float $avdollar): self
    {
        $this->avdollar = $avdollar;

        return $this;
    }

    public function getValid(): ?bool
    {
        return $this->valid;
    }

    public function setValid(?bool $valid): self
    {
        $this->valid = $valid;

        return $this;
    }
}
