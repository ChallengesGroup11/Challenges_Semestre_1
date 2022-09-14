<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\DetailRepository;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource]
#[ORM\Entity(repositoryClass: DetailRepository::class)]
class Detail
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column(length: 2)]
    private ?string $size = null;

    #[ORM\ManyToOne(inversedBy: 'details')]
    private ?Order $orderDelivery = null;

    #[ORM\ManyToOne(inversedBy: 'details')]
    private ?Pizza $pizza = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getSize(): ?string
    {
        return $this->size;
    }

    public function setSize(string $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getOrderDelivery(): ?Order
    {
        return $this->orderDelivery;
    }

    public function setOrderDelivery(?Order $orderDelivery): self
    {
        $this->orderDelivery = $orderDelivery;

        return $this;
    }

    public function getPizza(): ?Pizza
    {
        return $this->pizza;
    }

    public function setPizza(?Pizza $pizza): self
    {
        $this->pizza = $pizza;

        return $this;
    }
}
