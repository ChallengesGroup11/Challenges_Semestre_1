<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Controller\GetUserDirector;
use App\Repository\DirectorRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: DirectorRepository::class)]
#[ApiResource()]
#[Get(normalizationContext: ['groups' => ['director_get']])]
#[GetCollection(normalizationContext: ['groups' => ['director_cget']])]

class Director
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    #[Groups(['user_get','director_cget','director_get','driving_school_cget','user_cget'])]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'director', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['driving_school_cget','user_get','director_cget','director_get','user_cget'])]
    private ?User $userId = null;

    #[ORM\OneToOne(inversedBy: 'director', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['director_get','director_cget','driving_school_cget'])]
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
