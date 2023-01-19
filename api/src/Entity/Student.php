<?php

namespace App\Entity;

use App\Repository\StudentRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StudentRepository::class)]
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
}
