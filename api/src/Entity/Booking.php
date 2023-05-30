<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Delete;
use App\Repository\BookingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\Traits\Timer;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Controller\BookingPatchController;

#[ORM\Entity(repositoryClass: BookingRepository::class)]
// @ApiResource(attributes={"normalization_context": {"groups"={"todolist"}, "enable_max_depth"=true}})
#[ApiResource(operations: [
    new Patch (
        uriTemplate: '/bookings/student/{id}',
        controller: BookingPatchController::class,
        openapiContext: [
            'summary' => 'Editer un créneau',
        ],
        denormalizationContext: ['groups' => ['booking_write']],
        name: 'booking_patch'
    ),
    new Post(
        denormalizationContext: ['groups' => ['booking_write']],
        security: '(is_granted("ROLE_DIRECTOR")) or (is_granted("ROLE_ADMIN"))',
    )

]
)]
#[Get(
    normalizationContext: ['groups' => ['booking_get']]
)]
#[GetCollection(
    normalizationContext: ['groups' => ['booking_cget']]
)]

#[Patch(
    denormalizationContext: ['groups' => ['booking_write']],
    // security: 'is_granted("ROLE_DIRECTOR")'
)]

#[Delete(
    security: 'is_granted("ROLE_DIRECTOR")'
)]

// #[GET(normalizationContext: ['groups' => ['booking:read']])]
class Booking
{
    use Timer;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    #[Groups(['booking_get','booking_cget','driving_school_get','driving_school_cget'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['booking_get','get','booking_cget','booking_write','driving_school_get'])]
    private ?\DateTimeInterface $slotBegin = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['booking_get','get','booking_cget','booking_write','driving_school_get'])]
    private ?\DateTimeInterface $slotEnd = null;

    #[ORM\Column(length: 255,nullable: true)]
    #[Groups(['booking_get','get', 'booking_cget','driving_school_get'])]
    private ?string $comment = null;

    #[ORM\Column]
    #[Groups(['booking_get','get','booking_cget','booking_write','driving_school_get'])]
    private ?bool $statusValidate = null;

    #[ORM\Column]
    #[Groups(['booking_get','get','booking_cget','booking_write','driving_school_get'])]
    private ?bool $statusDone = null;

    #[ORM\ManyToMany(targetEntity: Student::class, inversedBy: 'bookings',fetch: "EAGER")]
    #[Groups(['booking_get','booking_cget','booking_write','driving_school_get','user_get'])]
    private Collection $studentId;

    #[ORM\ManyToMany(targetEntity: Monitor::class, inversedBy: 'bookings',fetch: "EAGER")]
    #[Groups(['booking_get', 'booking_cget','booking_write','driving_school_get'])]
    private Collection $monitorId;

    #[ORM\ManyToOne(inversedBy: 'bookings')]
    #[Groups(['booking_get','booking_cget','booking_write','driving_school_get','student_get'])]
    private ?DrivingSchool $drivingSchoolId = null;

    // #[ORM\ManyToMany(targetEntity: DrivingSchool::class, mappedBy: 'bookings',fetch: "EAGER")]
    // #[Groups(['booking_get','booking_write'])]
    // #[ApiProperty(fetchEager: true)]
    // private Collection $drivingSchoolId;

    // #[ORM\ManyToOne(inversedBy: 'bookings')]
    // #[Groups(['booking_get','booking_write','driving_school_get'])]
    // private ?DrivingSchool $drivingSchoolId = null;

    public function __construct()
    {
        $this->studentId = new ArrayCollection();
        $this->monitorId = new ArrayCollection();
        // $this->drivingSchoolId = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSlotBegin(): ?\DateTimeInterface
    {
        return $this->slotBegin;
    }

    public function setSlotBegin(\DateTimeInterface $slotBegin): self
    {
        $this->slotBegin = $slotBegin;

        return $this;
    }

    public function getSlotEnd(): ?\DateTimeInterface
    {
        return $this->slotEnd;
    }

    public function setSlotEnd(\DateTimeInterface $slotEnd): self
    {
        $this->slotEnd = $slotEnd;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function isStatusValidate(): ?bool
    {
        return $this->statusValidate;
    }

    public function setStatusValidate(bool $statusValidate): self
    {
        $this->statusValidate = $statusValidate;

        return $this;
    }

    public function isStatusDone(): ?bool
    {
        return $this->statusDone;
    }

    public function setStatusDone(bool $statusDone): self
    {
        $this->statusDone = $statusDone;

        return $this;
    }

    /**
     * @return Collection<int, Student>
     */
    public function getStudentId(): Collection
    {
        return $this->studentId;
    }

    public function addStudentId(Student $studentId): self
    {
        if (!$this->studentId->contains($studentId)) {
            $this->studentId[] = $studentId;
        }

        return $this;
    }

    public function removeStudentId(Student $studentId): self
    {
        $this->studentId->removeElement($studentId);

        return $this;
    }

    /**
     * @return Collection<int, Monitor>
     */
    public function getMonitorId(): Collection
    {
        return $this->monitorId;
    }

    public function addMonitorId(Monitor $monitorId): self
    {
        if (!$this->monitorId->contains($monitorId)) {
            $this->monitorId[] = $monitorId;
        }

        return $this;
    }

    public function removeMonitorId(Monitor $monitorId): self
    {
        $this->monitorId->removeElement($monitorId);

        return $this;
    }

    // /**
    //  * @return Collection<int, DrivingSchool>
    //  */
    // public function getDrivingSchoolId(): Collection
    // {
    //     return $this->drivingSchoolId;
    // }

    // public function addDrivingSchoolId(DrivingSchool $drivingSchoolId): self
    // {
    //     if (!$this->drivingSchoolId->contains($drivingSchoolId)) {
    //         $this->drivingSchoolId[] = $drivingSchoolId;
    //     }

    //     return $this;
    // }

    // public function removeDrivingSchoolId(DrivingSchool $drivingSchoolId): self
    // {
    //     $this->drivingSchoolId->removeElement($drivingSchoolId);

    //     return $this;
    // }

    // public function getDrivingSchoolId(): ?DrivingSchool
    // {
    //     return $this->drivingSchoolId;
    // }

    // public function setDrivingSchoolId(?DrivingSchool $drivingSchoolId): self
    // {
    //     $this->drivingSchoolId = $drivingSchoolId;

    //     return $this;
    // }

    public function getDrivingSchoolId(): ?DrivingSchool
    {
        return $this->drivingSchoolId;
    }

    public function setDrivingSchoolId(?DrivingSchool $drivingSchoolId): self
    {
        $this->drivingSchoolId = $drivingSchoolId;

        return $this;
    }
}
