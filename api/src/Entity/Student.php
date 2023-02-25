<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Controller\StudentPostController;
use App\Repository\StudentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\OpenApi\Model;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: StudentRepository::class)]
#[ApiResource(operations: [
    new Post(
        uriTemplate: '/student/pathCode',
        inputFormats: ['multipart' => ['multipart/form-data']],
        controller: StudentPostController::class,
        openapiContext: [
            'summary' => 'Ajouter le code de l\'étudiant',
            'requestBody' => [
                'content' => [
                    'multipart/form-data' => [
                        'schema' => [
                            'type' => 'object',
                            'properties' => [
                                'fileCode' => [
                                    'type' => 'string',
                                    'format' => 'binary'
                                ],
                                'fileCni' => [
                                    'type' => 'string',
                                    'format' => 'binary'
                                ],
                                'userId' => [
                                    'type' => 'integer'
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ],
    normalizationContext: ['groups' => ['student_get_path_code']],
    denormalizationContext: ['groups' => ['student_write_path_code']],
    name: 'student_path_code'
 )]
)]


#[GetCollection(
    normalizationContext: ['groups' => ['student_cget']],
    security: 'is_granted("ROLE_DIRECTOR", "ROLE_ADMIN")'
)]
//TODO CREER LE USER QUAND IL ENVOIE SES FILES
#[Get(normalizationContext: ['groups' => ['student_get']])]
class Student
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    #[Groups(['student_get'])]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['student_get'])]
    private ?int $nbHourDone;

    #[ApiProperty(types: ['https://localhost/contentUrl'])]
    #[Groups(['student_get', 'student_get_patch_code', 'student_cget'])]
    public ?string $contentUrlCode = null;

    #[Vich\UploadableField(mapping: "media_object_code", fileNameProperty: "filePathCode")]
    #[Groups(['student_write_path_code'])]
    public ?File $fileCode = null;

    #[ORM\Column(nullable: true)]
    public ?string $filePathCode = null;

    #[ApiProperty(types: ['https://localhost/contentUrl'])]
    #[Groups(['student_get', 'student_get_path'])]
    public ?string $contentUrlCni = null;

    #[Vich\UploadableField(mapping: "media_object", fileNameProperty: "filePathCni")]
    #[Groups(['student_write_path'])]
    public ?File $fileCni = null;

    #[ORM\Column(nullable: true)]
    public ?string $filePathCni = null;


//    #[ORM\Column(length: 255, nullable:true)]
//    #[Groups(['student_get'])]
//    private ?string $urlCodeCertification = null;
//
//    #[ORM\Column(length: 255, nullable:true)]
//    #[Groups(['student_get'])]
//    private ?string $urlCni = null;

    #[ORM\OneToOne(inversedBy: 'student', cascade: ['persist', 'remove'], fetch: "EAGER")]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(['user_get', 'student_cget'])]
    private ?User $userId = null;


    #[ORM\ManyToMany(targetEntity: Booking::class, mappedBy: 'studentId', fetch: "EAGER")]
    #[Groups(['student_get'])]
    #[ApiProperty(fetchEager: true)]
    private Collection $bookings;


    #[ORM\Column(nullable: true)]
    #[Groups(['student_get'])]
    private ?int $countCredit = null;

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

    /**
     * @return string|null
     */
    public function getFilePathCode(): ?string
    {
        return $this->filePathCode;
    }

    /**
     * @param string|null $filePathCode
     */
    public function setFilePathCode(?string $filePathCode): void
    {
        $this->filePathCode = $filePathCode;
    }

    /**
     * @return string|null
     */
    public function getFilePathCni(): ?string
    {
        return $this->filePathCni;
    }

    /**
     * @param string|null $filePathCni
     */
    public function setFilePathCni(?string $filePathCni): void
    {
        $this->filePathCni = $filePathCni;
    }


//    public function getUrlCodeCertification(): ?string
//    {
//        return $this->urlCodeCertification;
//    }
//
//    public function setUrlCodeCertification(string $urlCodeCertification): self
//    {
//        $this->urlCodeCertification = $urlCodeCertification;
//
//        return $this;
//    }
//
//    public function getUrlCni(): ?string
//    {
//        return $this->urlCni;
//    }
//
//    public function setUrlCni(string $urlCni): self
//    {
//        $this->urlCni = $urlCni;
//
//        return $this;
//    }

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


    public function getCountCredit(): ?int
    {
        return $this->countCredit;
    }

    public function setCountCredit(?int $countCredit): self
    {
        $this->countCredit = $countCredit;

        return $this;
    }
}
