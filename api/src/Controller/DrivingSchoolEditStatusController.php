<?php

namespace App\Controller;

use App\Entity\DrivingSchool;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Serializer\SerializerInterface;

#[asController]
class DrivingSchoolEditStatusController extends AbstractController
{
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    public function __invoke(DrivingSchool $drivingSchool, Request $request , SerializerInterface $serializer ): JsonResponse
    {
        $drivingSchool = $serializer->deserialize($request->getContent(), DrivingSchool::class, 'json', ['object_to_populate' => $drivingSchool]);
        $drivingSchool->setStatus(true);
        $this->em->persist($drivingSchool);
        $this->em->flush();

        return $this->json($drivingSchool, 201, ['success'], ['groups' => ['driving_school_get']]);
    }



}
