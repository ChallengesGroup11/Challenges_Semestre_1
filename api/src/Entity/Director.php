<?php

namespace App\Entity;

use App\Repository\DirectorRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;

#[ORM\Entity(repositoryClass: DirectorRepository::class)]
#[ApiResource]
class Director
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'director', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $userId = null;

    #[ORM\OneToOne(inversedBy: 'director', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?DrivingSchool $drivingSchoolId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?User
    {
        return $this->userId;
    }

    public function setUserId(User $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getDrivingSchoolId(): ?DrivingSchool
    {
        return $this->drivingSchoolId;
    }

    public function setDrivingSchoolId(DrivingSchool $drivingSchoolId): self
    {
        $this->drivingSchoolId = $drivingSchoolId;

        return $this;
    }
}
