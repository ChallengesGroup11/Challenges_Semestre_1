<?php

namespace App\Entity;

use App\Repository\BookingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\Traits\Timer;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;

#[ORM\Entity(repositoryClass: BookingRepository::class)]
#[ApiResource]
class Booking
{
    use Timer;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $slotBegin = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $slotEnd = null;

    #[ORM\Column(length: 255)]
    private ?string $comment = null;

    #[ORM\Column]
    private ?bool $statusValidate = null;

    #[ORM\Column]
    private ?bool $statusDone = null;

    #[ORM\ManyToMany(targetEntity: Student::class, inversedBy: 'bookings')]
    private Collection $studentId;

    #[ORM\ManyToMany(targetEntity: Monitor::class, inversedBy: 'bookings')]
    private Collection $monitorId;

    #[ORM\ManyToMany(targetEntity: DrivingSchool::class, inversedBy: 'bookings')]
    private Collection $drivingSchoolId;

    public function __construct()
    {
        $this->studentId = new ArrayCollection();
        $this->monitorId = new ArrayCollection();
        $this->drivingSchoolId = new ArrayCollection();
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

    /**
     * @return Collection<int, DrivingSchool>
     */
    public function getDrivingSchoolId(): Collection
    {
        return $this->drivingSchoolId;
    }

    public function addDrivingSchoolId(DrivingSchool $drivingSchoolId): self
    {
        if (!$this->drivingSchoolId->contains($drivingSchoolId)) {
            $this->drivingSchoolId[] = $drivingSchoolId;
        }

        return $this;
    }

    public function removeDrivingSchoolId(DrivingSchool $drivingSchoolId): self
    {
        $this->drivingSchoolId->removeElement($drivingSchoolId);

        return $this;
    }
}
