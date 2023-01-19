<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\StudentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StudentRepository::class)]
#[ApiResource]
class Student
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $nbHourDone;

    #[ORM\Column(length: 255)]
    private ?string $urlCodeCertification = null;

    #[ORM\Column(length: 255)]
    private ?string $urlCni = null;

    #[ORM\OneToOne(inversedBy: 'student', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $userId = null;

    #[ORM\ManyToMany(targetEntity: Booking::class, mappedBy: 'studentId')]
    private Collection $bookings;

    public function __construct()
    {
        $this->bookings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbHourDone(): ?int
    {
        return $this->nbHourDone;
    }

    public function setNbHourDone(int $nbHourDone): self
    {
        $this->nbHourDone = $nbHourDone;

        return $this;
    }

    public function getUrlCodeCertification(): ?string
    {
        return $this->urlCodeCertification;
    }

    public function setUrlCodeCertification(string $urlCodeCertification): self
    {
        $this->urlCodeCertification = $urlCodeCertification;

        return $this;
    }

    public function getUrlCni(): ?string
    {
        return $this->urlCni;
    }

    public function setUrlCni(string $urlCni): self
    {
        $this->urlCni = $urlCni;

        return $this;
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

    /**
     * @return Collection<int, Booking>
     */
    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    public function addBooking(Booking $booking): self
    {
        if (!$this->bookings->contains($booking)) {
            $this->bookings[] = $booking;
            $booking->addStudentId($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): self
    {
        if ($this->bookings->removeElement($booking)) {
            $booking->removeStudentId($this);
        }

        return $this;
    }
}
