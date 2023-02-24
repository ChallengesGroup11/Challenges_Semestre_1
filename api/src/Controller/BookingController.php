<?php

namespace App\Controller;

use App\Repository\BookingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Serializer\SerializerInterface;

#[asController]
class BookingController extends AbstractController
{
    public function __invoke( Request $request , SerializerInterface $serializer,
     BookingRepository $bookingRepository ) : JsonResponse
    {

      slotBegin: state.newSlot.slotBegin,
      slotEnd: state.newSlot.slotEnd,
      comment: '',
      statusValidate: false,
      statusDone: false,
      drivingSchoolId: [useStoreUser().drivingSchool],

      $slotBegin = json_decode($this->requestStack->getCurrentRequest()->getContent())->slotBegin;
      $slotEnd = json_decode($this->requestStack->getCurrentRequest()->getContent())->slotEnd;
      $comment = json_decode($this->requestStack->getCurrentRequest()->getContent())->comment;
      $statusValidate = json_decode($this->requestStack->getCurrentRequest()->getContent())->statusValidate;
      $statusDone = json_decode($this->requestStack->getCurrentRequest()->getContent())->statusDone;
      $drivingSchoolId = json_decode($this->requestStack->getCurrentRequest()->getContent())->drivingSchoolId;
        
        $booking = new Booking();

        $booking->setSlotBegin($slotBegin);
        $booking->setSlotEnd($slotEnd);
        $booking->setComment($comment);
        $booking->setStatusValidate($statusValidate);
        $booking->setStatusDone($statusDone);
        $booking->setDrivingSchoolId($drivingSchoolId);

        $lastIdBooking = $bookingRepository->searchLastBooking();

        



        $this->em->persist($booking);
        $this->em->flush();

        

        return $this->json($user, 201, ['success']);
    }

}
