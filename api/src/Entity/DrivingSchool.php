<?php

namespace App\Entity;

use App\Repository\DrivingSchoolRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\Traits\Timer;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;

#[ORM\Entity(repositoryClass: DrivingSchoolRepository::class)]
#[ApiResource]
class DrivingSchool
{
    use Timer;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $address;

    #[ORM\Column(length: 255)]
    private ?string $city;

    #[ORM\Column(length: 5)]
    private ?string $zipcode;

    #[ORM\Column(length: 14)]
    private ?string $siret = null;

    #[ORM\Column(length: 10)]
    private ?string $phoneNumber = null;

    #[ORM\Column(length: 255)]
    private ?string $urlKbis = null;

    #[ORM\Column]
    private ?bool $status = null;

    #[ORM\OneToOne(mappedBy: 'drivingSchoolId', cascade: ['persist', 'remove'])]
    private ?Director $director = null;

    #[ORM\OneToMany(mappedBy: 'drivingSchoolId', targetEntity: Monitor::class)]
    private Collection $monitors;

    #[ORM\ManyToMany(targetEntity: Booking::class, mappedBy: 'drivingSchoolId')]
    private Collection $bookings;

    public function __construct()
    {
        $this->monitors = new ArrayCollection();
        $this->bookings = new ArrayCollection();
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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(string $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(string $siret): self
    {
        $this->siret = $siret;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getUrlKbis(): ?string
    {
        return $this->urlKbis;
    }

    public function setUrlKbis(string $urlKbis): self
    {
        $this->urlKbis = $urlKbis;

        return $this;
    }

    public function isStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getDirector(): ?Director
    {
        return $this->director;
    }

    public function setDirector(Director $director): self
    {
        // set the owning side of the relation if necessary
        if ($director->getDrivingSchoolId() !== $this) {
            $director->setDrivingSchoolId($this);
        }

        $this->director = $director;

        return $this;
    }

    /**
     * @return Collection<int, Monitor>
     */
    public function getMonitors(): Collection
    {
        return $this->monitors;
    }

    public function addMonitor(Monitor $monitor): self
    {
        if (!$this->monitors->contains($monitor)) {
            $this->monitors[] = $monitor;
            $monitor->setDrivingSchoolId($this);
        }

        return $this;
    }

    public function removeMonitor(Monitor $monitor): self
    {
        if ($this->monitors->removeElement($monitor)) {
            // set the owning side to null (unless already changed)
            if ($monitor->getDrivingSchoolId() === $this) {
                $monitor->setDrivingSchoolId(null);
            }
        }

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
            $booking->addDrivingSchoolId($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): self
    {
        if ($this->bookings->removeElement($booking)) {
            $booking->removeDrivingSchoolId($this);
        }

        return $this;
    }
}
