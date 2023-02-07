<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Entity\Traits\Timer;
use App\Repository\PaymentRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;

#[ORM\Entity(repositoryClass: PaymentRepository::class)]
#[ApiResource()]
#[GetCollection(normalizationContext: ['groups' => ['payment_cget']],security: 'is_granted("ADMIN")')]
#[Get(normalizationContext: ['groups' => ['payment_get']])]
#[Post(
    normalizationContext: ['groups' => ['payment_get']],
    denormalizationContext: ['groups' => ['payment_write']],
)]
class Payment
{
    use Timer;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'payments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Package $packageId = null;

    #[ORM\ManyToOne(inversedBy: 'payments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $userId = null;

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
}
