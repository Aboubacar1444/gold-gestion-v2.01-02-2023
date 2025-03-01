<?php

namespace App\Entity;

use App\Repository\SocietyRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SocietyRepository::class)]
class Society
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;


    #[ORM\Column(type: 'string', length: 255)]
    private string $job;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private string $logo;

    #[ORM\Column(type: 'float', nullable: true)]
    private float $caisse;

    #[ORM\Column(type: 'string', length: 255)]
    private string $tel;

    #[ORM\Column(type: 'text', nullable: true)]
    private string $description;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private string $email;




   public function setId(int $id): static
   {
        $this->id = $id;
        return $this;
   }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getJob(): ?string
    {
        return $this->job;
    }

    public function setJob(string $job): self
    {
        $this->job = $job;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getCaisse(): ?float
    {
        return $this->caisse;
    }

    public function setCaisse(?float $caisse): self
    {
        $this->caisse = $caisse;

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



    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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



}
