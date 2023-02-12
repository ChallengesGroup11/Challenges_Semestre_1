<?php

namespace App\Controller;

use App\Repository\DirectorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Serializer\SerializerInterface;

#[asController]
class GetUserDirector extends AbstractController
{
    public function __invoke( Request $request , SerializerInterface $serializer, DirectorRepository $directorRepository) : JsonResponse
    {
        $user = $directorRepository->findAllDirector();
        return $this->json($user, 201, ['success']);
    }

}
