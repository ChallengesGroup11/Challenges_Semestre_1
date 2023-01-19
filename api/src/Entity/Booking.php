<?php

namespace App\Entity;

use App\Repository\BookingRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookingRepository::class)]
class Booking
{
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
}
