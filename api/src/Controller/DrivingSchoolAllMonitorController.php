<?php

namespace App\Controller;

use App\Repository\DrivingSchoolRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Serializer\SerializerInterface;

#[asController]
class DrivingSchoolAllMonitorController extends AbstractController
{
    public function __invoke( Request $request , SerializerInterface $serializer, DrivingSchoolRepository $drivingSchoolRepository) : JsonResponse
    {

        $monitors = $drivingSchoolRepository->findAllMonitorByDrivingSchool($request->get('id'));

        return $this->json($monitors, 201, ['success']);
    }
}
