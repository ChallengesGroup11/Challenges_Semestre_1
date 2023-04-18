<?php

namespace App\Entity;

use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\PackageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\Timer;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;


#[ORM\Entity(repositoryClass: PackageRepository::class)]
#[ApiResource]
#[Get(
    normalizationContext: ['groups' => ['package_get']]
)]
#[GetCollection(
    normalizationContext: ['groups' => ['package_cget']]
)]
#[Post(
    normalizationContext: ['groups' => ['package_get']],
    denormalizationContext: ['groups' => ['package_post']],
    // security: 'is_granted("ROLE_ADMIN")'
)]
#[Patch(
    denormalizationContext: ['groups' => ['package_patch']],
    security: 'is_granted("ROLE_ADMIN")'
)]
#[Delete(
    security: 'is_granted("ROLE_ADMIN")'
)]
class Package
{
    use Timer;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    #[Groups(['package_get', 'package_cget'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['package_get', 'package_cget','package_patch','package_post'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['package_get', 'package_cget','package_patch','package_post'])]
    private ?string $description = null;

    #[ORM\Column]
    #[Groups(['package_get', 'package_cget','package_patch','package_post'])]
    private ?int $nbCredit = null;

    #[ORM\Column]
    #[Groups(['package_get', 'package_cget','package_patch','package_post'])]
    private ?float $price = null;

    #[ORM\OneToMany(mappedBy: 'packageId', targetEntity: Payment::class)]
    private Collection $payments;

    public function __construct()
    {
        $this->payments = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getNbCredit(): ?int
    {
        return $this->nbCredit;
    }

    public function setNbCredit(int $nbCredit): self
    {
        $this->nbCredit = $nbCredit;

        return $this;
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

    /**
     * @return Collection<int, Payment>
     */
    public function getPayments(): Collection
    {
        return $this->payments;
    }

    public function addPayment(Payment $payment): self
    {
        if (!$this->payments->contains($payment)) {
            $this->payments[] = $payment;
            $payment->setPackageId($this);
        }

        return $this;
    }

    public function removePayment(Payment $payment): self
    {
        if ($this->payments->removeElement($payment)) {
            // set the owning side to null (unless already changed)
            if ($payment->getPackageId() === $this) {
                $payment->setPackageId(null);
            }
        }

        return $this;
    }
}
