<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Controller\DrivingSchoolEditStatusController;
use App\Repository\DrivingSchoolRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Entity\Traits\Timer;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\OpenApi\Model;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;


#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: DrivingSchoolRepository::class)]
#[ApiResource(operations: [
    new Patch(
        uriTemplate: '/driving_schools/{id}/edit_status',
        controller: DrivingSchoolEditStatusController::class,
        openapiContext: [
            'summary' => 'Editer le status d\'une auto-école',
        ],
        denormalizationContext: ['groups' => ['driving_school_patch']],
        security: '(is_granted("ROLE_DIRECTOR")) or (is_granted("ROLE_ADMIN"))',
        name: 'driving_school_edit_status'
    ),

    new Post(
        inputFormats: ['multipart' => ['multipart/form-data']],
        normalizationContext: ['groups' => ['driving_school_get']],
        denormalizationContext: ['groups' => ['driving_school_write']],
        security: 'is_granted("ROLE_ADMIN") or is_granted("ROLE_DIRECTOR")',
    ),
],
)]
#[GetCollection(
    normalizationContext: ['groups' => ['driving_school_cget']],
)]
#[Get(
    normalizationContext: ['groups' => ['driving_school_get']]
)]
#[Put(
    security: 'is_granted("ROLE_ADMIN")'
)]
#[Patch(
    security: 'is_granted("ROLE_ADMIN") or is_granted("ROLE_DIRECTOR")'
)]
#[Delete(
    security: 'is_granted("ROLE_ADMIN")'
)]
class DrivingSchool
{
    use Timer;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    #[Groups(['driving_school_cget', 'driving_school_get'])]
    private ?int $id = null;


    #[ORM\Column(length: 255)]
    #[Groups(['driving_school_cget', 'driving_school_get', 'driving_school_write','monitor_get','director_cget','monitor_cget'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['director_get', 'driving_school_cget', 'driving_school_get', 'driving_school_write','monitor_get'])]
    private ?string $address;

    #[ORM\Column(length: 255)]
    #[Groups(['director_get', 'driving_school_cget', 'driving_school_get', 'driving_school_write','monitor_get'])]
    private ?string $city;

    #[ORM\Column(length: 5)]
    #[Groups(['director_get', 'driving_school_cget', 'driving_school_get', 'driving_school_write','monitor_get'])]
    private ?string $zipcode;

    #[ORM\Column(length: 14)]
    #[Groups(['director_get', 'driving_school_cget', 'driving_school_get', 'driving_school_write'])]
    private ?string $siret = null;

    #[ORM\Column(length: 10)]
    #[Groups(['director_get', 'driving_school_cget', 'driving_school_get', 'driving_school_write','monitor_get'])]
    private ?string $phoneNumber = null;

    #[ApiProperty(types: ['https://localhost/contentUrl'])]
    #[Groups(['driving_school_get','driving_school_cget'])]
    public ?string $contentUrl = null;

    #[Vich\UploadableField(mapping: "media_object", fileNameProperty: "filePath")]
    #[Groups(['driving_school_write'])]
    public ?File $file = null;

    #[ORM\Column(nullable: true)]
    public ?string $filePath = null;


    #[ORM\Column]
    #[Groups(['driving_school_cget'])]
    private ?bool $status = false;

    #[ORM\OneToOne(mappedBy: 'drivingSchoolId', cascade: ['persist', 'remove'],fetch: "EAGER")]
    #[Groups(['driving_school_cget', 'driving_school_get', 'driving_school_write'])]
    private ?Director $director = null;

    #[ORM\OneToMany(mappedBy: 'drivingSchoolId', cascade: ['persist', 'remove'] ,targetEntity: Monitor::class,fetch: "EAGER")]
    #[Groups(['driving_school_get','driving_school_cget'])]
    private Collection $monitors;

    #[ORM\OneToMany(mappedBy: 'drivingSchoolId', cascade: ['persist', 'remove'], targetEntity: Booking::class)]
    #[Groups(['driving_school_get','booking_cget',"user_get"])]
    private Collection $bookings;

    public function __construct()
    {
        $this->monitors = new ArrayCollection();
        // $this->bookings = new ArrayCollection();
        // $this->bookings = new ArrayCollection();
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

    // /**
    //  * @return Collection<int, Booking>
    //  */
    // public function getBookings(): Collection
    // {
    //     return $this->bookings;
    // }

    // public function addBooking(Booking $booking): self
    // {
    //     if (!$this->bookings->contains($booking)) {
    //         $this->bookings[] = $booking;
    //         $booking->addDrivingSchoolId($this);
    //     }

    //     return $this;
    // }

    // public function removeBooking(Booking $booking): self
    // {
    //     if ($this->bookings->removeElement($booking)) {
    //         $booking->removeDrivingSchoolId($this);
    //     }

    //     return $this;
    // }

    /**
     * @return string|null
     */
    public function getFilePath(): ?string
    {
        return $this->filePath;
    }

    /**
     * @param string|null $filePath
     */
    public function setFilePath(?string $filePath): void
    {
        $this->filePath = $filePath;
    }

    // /**
    //  * @return Collection<int, Booking>
    //  */
    // public function getBookings(): Collection
    // {
    //     return $this->bookings;
    // }

    // public function addBookings(Booking $bookings): self
    // {
    //     if (!$this->bookings->contains($bookings)) {
    //         $this->bookings[] = $bookings;
    //         $bookings->setDrivingSchoolId($this);
    //     }

    //     return $this;
    // }

    // public function removeBookings(Booking $bookings): self
    // {
    //     if ($this->bookings->removeElement($bookings)) {
    //         // set the owning side to null (unless already changed)
    //         if ($bookings->getDrivingSchoolId() === $this) {
    //             $bookings->setDrivingSchoolId(null);
    //         }
    //     }

    //     return $this;
    // }

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
            $booking->setDrivingSchoolId($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): self
    {
        if ($this->bookings->removeElement($booking)) {
            // set the owning side to null (unless already changed)
            if ($booking->getDrivingSchoolId() === $this) {
                $booking->setDrivingSchoolId(null);
            }
        }

        return $this;
    }
}
