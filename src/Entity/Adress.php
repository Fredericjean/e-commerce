<?php

namespace App\Entity;

use App\Repository\AdressRepository;
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
}
