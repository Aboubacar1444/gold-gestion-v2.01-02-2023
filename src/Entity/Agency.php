<?php

namespace App\Entity;

use App\Repository\AgencyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AgencyRepository::class)]
class Agency
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'string', length: 255)]
    private string $tel;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private string $caisse;

    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'agency')]
    private Collection $users;


    #[ORM\OneToMany(targetEntity: Transfert::class, mappedBy: 'transagency')]
    private Collection  $transferts;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $email;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->transferts = new ArrayCollection();
    }

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

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(string $tel): self
    {
        $this->tel = $tel;

        return $this;
    }

    public function getCaisse(): ?string
    {
        return $this->caisse;
    }

    public function setCaisse(?string $caisse): self
    {
        $this->caisse = $caisse;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setAgency($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        // set the owning side to null (unless already changed)
        if ($this->users->removeElement($user) && $user->getAgency() === $this) {
            $user->setAgency(null);
        }

        return $this;
    }
    public function __toString()
    {
        return $this->getName();
    }





    /**
     * @return Collection|Transfert[]
     */
    public function getTransferts(): Collection
    {
        return $this->transferts;
    }

    public function addTransfert(Transfert $transfert): self
    {
        if (!$this->transferts->contains($transfert)) {
            $this->transferts[] = $transfert;
            $transfert->setTransagency($this);
        }

        return $this;
    }

    public function removeTransfert(Transfert $transfert): self
    {
        // set the owning side to null (unless already changed)
        if ($this->transferts->removeElement($transfert) && $transfert->getTransagency() === $this) {
            $transfert->setTransagency(null);
        }

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
