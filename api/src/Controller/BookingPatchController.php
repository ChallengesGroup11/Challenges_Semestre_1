<?php

namespace App\Controller;

use App\Entity\Booking;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Repository\StudentRepository;
use Symfony\Component\Security\Core\Security;

#[AsController]
class BookingPatchController extends AbstractController
{

    public function __construct(
        private RequestStack $requestStack,
    ) {
    }

    public function __invoke(Booking $booking, Request $request, SerializerInterface $serializer, StudentRepository $studentRepository, EntityManagerInterface $em, Security $security): JsonResponse
    {
        // $booking = $serializer->deserialize($request->getContent(), Booking::class, 'json', ['object_to_populate' => $booking]);

        $user = $security->getUser();
        if (in_array('ROLE_DIRECTOR', $user->getRoles()) != true) {
            $studentURI = json_decode($this->requestStack->getCurrentRequest()->getContent())->studentId[0];
            // extract the id from the URI
            $studentId = substr($studentURI, strrpos($studentURI, '/') + 1);

            $student = $studentRepository->find($studentId);

            $count_credit = $student->getCountCredit();
            if ($count_credit < 2) {
                return $this->json(['error' => 'Student has less than 2 count_credit'], 400);
            }
        }

        $em->persist($booking);
        $em->flush();
        return $this->json($booking, 200, ['success']);
    }
}
