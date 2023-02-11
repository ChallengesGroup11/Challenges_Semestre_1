<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\SerializerInterface;


#[AsController]
class GetCurrentUserController extends AbstractController
{
    public function __invoke(UserRepository $userRepository, Request $request, SerializerInterface $serializer, Security $security) : Response
    {
        $user = $security->getUser();


        if ($user instanceof User) {
//            $user = $serializer->serialize($user,'json');
//            return $this->json($user, 201, ['success'], ['groups' => ['user_cget']]);
            return new Response($serializer->serialize($user, 'json'), 200, ["Content-Type" => "application/json"]);
            // return 'ok'
        }

        return $this->json(null, 404);

    }
}
