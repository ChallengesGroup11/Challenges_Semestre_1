<?php
namespace App\Controller;
use App\Entity\Payment;
use App\Repository\PackageRepository;
use App\Repository\StudentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Charge;
use Stripe\Token;
use Symfony\Component\HttpFoundation\Request;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class DecrementCreditController extends AbstractController
{
    public function __construct(
        private RequestStack $requestStack,
    ) {}
    public function __invoke(Request $request, EntityManagerInterface $em,StudentRepository $studentRepository)
    {

      // $drivingSchool = $serializer->deserialize($request->getContent(), DrivingSchool::class, 'json', ['object_to_populate' => $drivingSchool]);
      // $drivingSchool->setStatus(true);
      // $this->em->persist($drivingSchool);
      // $this->em->flush();



      $parameters = json_decode($this->requestStack->getCurrentRequest()->getContent(), true);
      $numberCreditToDecrement = $parameters['countCredit'];
      $student = $studentRepository->find($parameters['studentId']);


      $studentRepository->decrementCountCredit($student, $numberCreditToDecrement, true );


            return $this->json([
                'type'=>'success',
                'message' => 'La décrementation a bien été effectuée',
            ], 200);

    }
}
