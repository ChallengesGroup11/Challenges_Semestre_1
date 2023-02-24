<?php

namespace App\Entity;


use App\Entity\Traits\Timer;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PaymentRepository::class)]
#[ApiResource()]
class Payment
{
    use Timer;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    #[Groups(['payment_get', 'payment_cget'])]
    private ?int $id = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPackageId(): ?Package
    {
        return $this->packageId;
    }

    public function setPackageId(?Package $packageId): self
    {
        $this->packageId = $packageId;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->userId;
    }

    public function setUserId(?User $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getDateAction(): ?\DateTimeInterface
    {
        return $this->date_action;
    }

    public function setDateAction(\DateTimeInterface $date_action): self
    {
        $this->date_action = $date_action;

        return $this;
    }
}
