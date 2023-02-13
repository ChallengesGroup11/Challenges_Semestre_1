<?php

namespace App\Controller;

use App\Entity\Student;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Serializer\SerializerInterface;

#[asController]
class StudentEditStatusController extends AbstractController
{
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    public function __invoke(Student $student, Request $request , SerializerInterface $serializer ): JsonResponse
    {
        $student = $serializer->deserialize($request->getContent(), Student::class, 'json', ['object_to_populate' => $student]);
        $student->setStatus(true);
        $this->em->persist($student);
        $this->em->flush();

        return $this->json($student, 201, ['success'], ['groups' => ['student_get']]);
    }



}