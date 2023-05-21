<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Patch;
use App\Controller\MonitorEditStatusController;
use App\Repository\MonitorRepository;
use App\Controller\DeleteMonitorController;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MonitorRepository::class)]
#[ApiResource(operations: [
    new Patch(
        uriTemplate: '/monitors/{id}/edit_status',
        controller: MonitorEditStatusController::class,
        openapiContext: [
            'summary' => 'Editer le status d\'un moniteur',
        ],
        denormalizationContext: ['groups' => ['monitor_patch']],
        security: '(is_granted("ROLE_DIRECTOR")) or (is_granted("ROLE_ADMIN"))',
        name: 'monitor_edit_status'
    ),
    new Delete(
        uriTemplate: '/monitors/{id}/delete',
        controller: DeleteMonitorController::class,
        openapiContext: ['description' => 'Delete Monitor'],
    ),

    new GetCollection(
        uriTemplate: '/monitors/getAll',
        openapiContext: ['description' => 'Get all monitors'],
        normalizationContext: ['groups' => ['monitor_cget']],
    ),

    ])
]
#[GetCollection(
    normalizationContext: ['groups' => ['monitor_cget']],
    security: 'is_granted("ROLE_ADMIN")',
)]
#[Get(normalizationContext: ['groups' => ['monitor_get']])]
#[Delete(
    security: '(is_granted("ROLE_DIRECTOR")) or (is_granted("ROLE_ADMIN"))',
)]
class Monitor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    #[Groups(['monitor_get','monitor_cget','user_get','driving_school_get'])]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'monitor', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(['booking_get','booking_cget', 'monitor_get', 'monitor_cget','user_cget','user_get'])]
    private ?User $userId = null;

    #[ORM\ManyToOne(inversedBy: 'monitors')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['monitor_cget',"monitor_get",'user_get','user_write'])]
    private ?DrivingSchool $drivingSchoolId = null;

    #[ORM\ManyToMany(targetEntity: Booking::class, mappedBy: 'monitorId')]
    #[Groups(['booking_cget', 'monitor_get', 'monitor_cget'])]
    private Collection $bookings;

    public function __construct()
    {
        $this->bookings = new ArrayCollection();
    }

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

    public function setDrivingSchoolId(?DrivingSchool $drivingSchoolId): self
    {
        $this->drivingSchoolId = $drivingSchoolId;

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
            $booking->addMonitorId($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): self
    {
        if ($this->bookings->removeElement($booking)) {
            $booking->removeMonitorId($this);
        }

        return $this;
    }
}
