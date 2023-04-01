<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Serializer\SerializerInterface;

#[asController]
class UserEditStatusController extends AbstractController
{
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    public function __invoke(User $user, Request $request , SerializerInterface $serializer ): JsonResponse
    {
        $user = $serializer->deserialize($request->getContent(), User::class, 'json', ['object_to_populate' => $user]);
        $user->setStatus(true);
        $this->em->persist($user);
        $this->em->flush();

        return $this->json($user, 201, ['success'], ['groups' => ['user_get']]);
    }



}
