<?php

namespace App\Entity;

use App\Repository\FruitRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FruitRepository::class)]
class Fruit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $family = null;

    #[ORM\Column(length: 255)]
    private ?string $taxo_order = null;

    #[ORM\Column(length: 255)]
    private ?string $genus = null;

    #[ORM\OneToOne(mappedBy: 'fruit_id', cascade: ['persist', 'remove'])]
    private ?Nutrition $nutrition = null;

    #[ORM\Column]
    private ?int $remote_id = null;

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

    public function getFamily(): ?string
    {
        return $this->family;
    }

    public function setFamily(string $family): self
    {
        $this->family = $family;

        return $this;
    }

    public function getTaxoOrder(): ?string
    {
        return $this->taxo_order;
    }

    public function setTaxoOrder(string $taxo_order): self
    {
        $this->taxo_order = $taxo_order;

        return $this;
    }

    public function getGenus(): ?string
    {
        return $this->genus;
    }

    public function setGenus(string $genus): self
    {
        $this->genus = $genus;

        return $this;
    }

    public function getNutrition(): ?Nutrition
    {
        return $this->nutrition;
    }

    public function setNutrition(Nutrition $nutrition): self
    {
        // set the owning side of the relation if necessary
        if ($nutrition->getFruitId() !== $this) {
            $nutrition->setFruitId($this);
        }

        $this->nutrition = $nutrition;

        return $this;
    }

    public function getRemoteId(): ?int
    {
        return $this->remote_id;
    }

    public function setRemoteId(int $remote_id): self
    {
        $this->remote_id = $remote_id;

        return $this;
    }
}
