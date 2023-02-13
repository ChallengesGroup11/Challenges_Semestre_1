<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Controller\AddCreditController;
use App\Entity\Traits\Timer;
use App\Repository\PaymentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PaymentRepository::class)]
#[ApiResource(operations:[
    new Post(
        uriTemplate: '/payments',
        controller: AddCreditController::class,
        normalizationContext: ['groups' => ['payment_get']],
        denormalizationContext: ['groups' => ['payment_write']],
    )
    ]
)]
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
    #[Groups(['payment_get', 'payment_cget'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'payments')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['payment_get', 'payment_cget','payment_write'])]
    private ?Package $packageId = null;

    #[ORM\ManyToOne(inversedBy: 'payments')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['payment_get', 'payment_cget','payment_write'])]
    private ?User $userId = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['payment_get', 'payment_cget','payment_write'])]
    private ?\DateTimeInterface $date_action = null;

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
