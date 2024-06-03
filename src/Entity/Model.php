<?php

namespace App\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\EnableTrait;
use App\Repository\ModelRepository;
use App\Entity\Traits\DateTimeTrait;
use Symfony\Component\Validator\Constraints as Assert ;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: ModelRepository::class)]
#[UniqueEntity(
    fields: ['name'],
    message: 'Ce nom de marque est déjà utilisé',
)]
#[ORM\HasLifecycleCallbacks]
class Model
{
    use EnableTrait,
    DateTimeTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

#[Assert\NotBlank(message:"Le nom de la marque ne peut-être nul")]
#[Assert\Length(
    max:180,
    maxMessage:"Le nom de la marque ne peut pas dépasser 180 caractères",
    min: 3,
    minMessage: "Le nom de la marque doit excéder 3 charactères"
)]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Gedmo\Slug(fields: ['name'])]
    private ?string $slug = null;

    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }
}
