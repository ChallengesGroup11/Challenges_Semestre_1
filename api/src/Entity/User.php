<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use App\Controller\CheckAccountController;
use App\Controller\GetCurrentUserController;
use App\Controller\ResetPasswordController;
use App\Controller\SignUpStudentController;
use App\Controller\SignUpDirectorController;
use App\Controller\CreateMonitorController;
use App\Entity\Traits\Timer;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use http\Env\Request;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;
use App\Controller\UserEditStatusController;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ApiResource(operations: [
    new GetCollection(
        uriTemplate: '/me',
        controller: GetCurrentUserController::class,
        openapiContext: ['description' => 'Get current user'],
    ),
    new Post(
        uriTemplate: '/signup/student',
        controller: SignUpStudentController::class,
        openapiContext: ['description' => 'Signup User'],
        denormalizationContext: ['groups' => ['user_write']],
    ),
    new Post(
        uriTemplate: '/signup/director',
        controller: SignUpDirectorController::class,
        openapiContext: ['description' => 'Signup Director'],
        denormalizationContext: ['groups' => ['user_write']],
    ),
    new Patch(
        uriTemplate: '/users/{id}/edit_status',
        controller: UserEditStatusController::class,
        openapiContext: [
            'summary' => 'Editer le status d\'un user',
        ],
        denormalizationContext: ['groups' => ['user_patch']],
        security: '(is_granted("ROLE_DIRECTOR") and object.getDirector() == user) or (is_granted("ROLE_ADMIN"))',
        name: 'user_edit_status'
    ),
    new Post(
        uriTemplate: '/CheckAccount',
        controller: CheckAccountController::class,
        openapiContext: [
            'requestBody' => [
                'content' => [
                    'application/json' => [
                        'schema' => [
                            'type' => 'object',
                            'properties' => [
                                'token' => [
                                    'type' => 'string',
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
        description: 'Check account'
    ),
    new Post(
        uriTemplate: 'director/create_monitor',
        controller: CreateMonitorController::class,
        openapiContext: ['description' => 'Create Monitor'],
        denormalizationContext: ['groups' => ['user_write']],
    ),
])]

#[GetCollection(
    normalizationContext: ['groups' => ['user_cget']],
    security: 'is_granted("ROLE_ADMIN")',
)]
#[Get(
    normalizationContext: ['groups' => ['user_get']],
    security: 'object.getId() == user.getId()'
)]
#[Patch(
    denormalizationContext: ['groups' => ['user_patch']],

)]
#[Patch(
    uriTemplate: '/users/reset/password',
    controller: ResetPasswordController::class,
    security: 'object.getId() == user.getId()',
    name: 'reset-password'
)]
#[Delete(
    security: 'is_granted("ROLE_ADMIN")'
)]
#[UniqueEntity('email', message: 'L\'email {{ value }} existe déjà.')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{

    use Timer;


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    #[Groups([ 'user_cget', 'user_get', 'student_get', 'student_cget','director_get','director_cget'])]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Groups(['user_get', 'user_cget', 'user_write', 'monitor_get', 'monitor_cget','director_cget','director_get'])]
    #[NotBlank]
    private ?string $email = null;

    #[ORM\Column]
    #[Groups(['user_get', 'user_cget', 'user_write'])]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Groups(['user_write'])]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['user_write','user_get', 'user_cget'])]
    private ?string $token = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['user_get', 'user_cget', 'user_write', 'monitor_get', 'monitor_cget','director_cget'])]
    private ?bool $status = null;

    #[ORM\OneToOne(mappedBy: 'userId', cascade: ['persist', 'remove'],fetch: "EAGER")]
    #[Groups(['user_get', 'user_cget', 'student_get', 'student_cget'])]
    private ?Student $student = null;

    #[ORM\OneToOne(mappedBy: 'userId', cascade: ['persist', 'remove'],fetch: "EAGER")]
    #[Groups(['user_get', 'user_cget', 'director_get', 'director_cget'])]
    private ?Director $director = null;

    #[ORM\OneToOne(mappedBy: 'userId', cascade: ['persist', 'remove'],fetch: "EAGER")]
    #[Groups(['user_get', 'user_cget', 'monitor_get', 'monitor_cget','user_write'])]
    private ?Monitor $monitor = null;

    #[ORM\OneToMany(mappedBy: 'userId', targetEntity: Payment::class,fetch: "EAGER")]
    #[Groups(['user_cget'])]
    private Collection $payments;

    #[ORM\Column(length: 255)]
    #[Groups([ 'user_get', 'user_cget', 'user_patch', 'user_write', 'monitor_get', 'monitor_cget','student_get','driving_school_get','driving_school_cget','director_cget','director_write','director_get'])]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    #[Groups([ 'user_get',  'user_cget', 'user_patch',  'user_write', 'monitor_get', 'monitor_cget', 'student_get', 'student_cget','driving_school_get','driving_school_cget','director_cget','director_write','director_get'])]
    private ?string $lastname = null;


    public function __construct()
    {
        $this->payments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;

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

    public function getStudent(): ?Student
    {
        return $this->student;
    }

    public function setStudent(Student $student): self
    {
        // set the owning side of the relation if necessary
        if ($student->getUserId() !== $this) {
            $student->setUserId($this);
        }

        $this->student = $student;

        return $this;
    }

    public function getDirector(): ?Director
    {
        return $this->director;
    }

    public function setDirector(Director $director): self
    {
        // set the owning side of the relation if necessary
        if ($director->getUserId() !== $this) {
            $director->setUserId($this);
        }

        $this->director = $director;

        return $this;
    }

    public function getMonitor(): ?Monitor
    {
        return $this->monitor;
    }

    public function setMonitor(Monitor $monitor): self
    {
        // set the owning side of the relation if necessary
        if ($monitor->getUserId() !== $this) {
            $monitor->setUserId($this);
        }

        $this->monitor = $monitor;

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
            $payment->setUserId($this);
        }

        return $this;
    }

    public function removePayment(Payment $payment): self
    {
        if ($this->payments->removeElement($payment)) {
            // set the owning side to null (unless already changed)
            if ($payment->getUserId() === $this) {
                $payment->setUserId(null);
            }
        }

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

}
