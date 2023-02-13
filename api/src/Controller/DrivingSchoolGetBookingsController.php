<?php

namespace App\Controller;

use App\Entity\DrivingSchool;
use App\Repository\DrivingSchoolRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Serializer\SerializerInterface;

#[asController]
class DrivingSchoolGetBookingsController extends AbstractController
{
    public function __construct(EntityManagerInterface $em, DrivingSchoolRepository $drivingSchoolRepository)
    {
        $this->em = $em;
        $this->drivingSchoolRepository = $drivingSchoolRepository;
    }
    public function __invoke(DrivingSchool $drivingSchool, Request $request , SerializerInterface $serializer ): JsonResponse
    {
        $drivingSchool = $this->drivingSchoolRepository->getBookings($drivingSchool->getId());
        $data = $serializer->serialize($drivingSchool, 'json', ['groups' => ['driving_school_get_bookings']]);
        return new JsonResponse($data, 200, [], true);
    }

}
