<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;


#[AsController]
class GetUserDoneByDrivingSchoolController extends AbstractController
{
    public function __invoke( Request $request , SerializerInterface $serializer, UserRepository $userRepository) : JsonResponse
    {
        $user = $userRepository->findAllUserDoneByDrivingSchool($request->get('id'));
        return $this->json($user, 201, ['success']);
    }
}
