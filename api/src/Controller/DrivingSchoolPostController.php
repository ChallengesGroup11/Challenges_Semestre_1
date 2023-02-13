<?php

namespace App\Controller;

use App\Repository\DirectorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use App\Entity\DrivingSchool;

#[AsController]
class DrivingSchoolPostController extends AbstractController
{
    public function __invoke(Request $request, DirectorRepository $directorRepository): DrivingSchool
    {
        $uploadedFile = $request->files->get('file');
        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }
        $drivingSchool = new DrivingSchool();
        $drivingSchool->file = $uploadedFile;
        $director = $directorRepository->find($request->get('director'));
        $drivingSchool->setDirector($director);
        $drivingSchool->setName($request->get('name'));
        $drivingSchool->setAddress($request->get('address'));
        $drivingSchool->setCity($request->get('city'));
        $drivingSchool->setZipCode($request->get('zipCode'));
        $drivingSchool->setPhoneNumber($request->get('phone_number'));
        $drivingSchool->setSiret($request->get('siret'));
        $drivingSchool->setCreatedAt(new \DateTime());
        $drivingSchool->setUpdatedAt(new \DateTime());
        $drivingSchool->setStatus('pending');
        

        return $drivingSchool;
    }
}
