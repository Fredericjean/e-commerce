<?php

namespace App\Entity;

use App\Repository\AdressRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AdressRepository::class)]
class Adress
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank()]
    #[Assert\Length(
        min: 3,
        minMessage: "L'adresse ne peut pas faire moins de {{ limit }} caractères",
        max: 255,
        maxMessage :"L'adresse' ne peut pas faire plus de {{ limit }} caractères"
    )]
    private ?string $adress = null;

    #[ORM\Column(length: 5)]
    #[Assert\NotBlank()]
    #[Assert\Length(
        min: 5,
        minMessage: "Le code postal doit faire exactement {{ limit }} caractères",
        max: 5,
        maxMessage :"Le code postal doit faire exactement {{ limit }} caractères"
    )]
    private ?string $zip_code = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank()]
    #[Assert\Length(
        min: 3,
        minMessage: "Le nom de la ville ne peut pas faire moins de {{ limit }} caractères",
        max: 255,
        maxMessage :"Le nom de la ville ne peut pas faire plus de {{ limit }} caractères"
    )]
    private ?string $city = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'adresses')]
    private Collection $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): static
    {
        $this->adress = $adress;

        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->zip_code;
    }

    public function setZipCode(string $zip_code): static
    {
        $this->zip_code = $zip_code;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addAdress($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeAdress($this);
        }

        return $this;
    }
}
