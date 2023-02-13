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
class AddCreditController extends AbstractController
{
    public function __construct(
        private RequestStack $requestStack,
    ) {}
    public function __invoke(Request $request, EntityManagerInterface $em,StudentRepository $studentRepository, PackageRepository $packageRepository)
    {

        try {
            $parameters = json_decode($this->requestStack->getCurrentRequest()->getContent(), true);




            $card_number = (trim($parameters['cardNumber']));
            $card_exp_month = (trim($parameters['cardMonth']));
            $card_exp_year = (trim($parameters['cardYear']));
            $card_cvv = (trim($parameters['cardCvv']));
            $package = ($parameters['package']);


            Stripe::setApiKey($_ENV['STRIPE_SECRET']);

            $token = Token::create([
                "card" => [
                    "number" => $card_number,
                    "exp_month" => $card_exp_month,
                    "exp_year" => $card_exp_year,
                    "cvc" => $card_cvv,
                ]
            ]);

            Charge::create ([
                "amount" => $package['price'],
                "currency" => "usd",
                "source" => $token,
                "description" => $package['description']
            ]);

            $packageEntity = $packageRepository->find($package['id']);

            $payment = new Payment();
            $payment->setPackageId($packageEntity);
            $payment->setUserId($this->getUser());
            $payment->setDateAction(new \DateTime());
            $em->persist($payment);
            $em->flush();

            $student = $studentRepository->findOneBy(['userId' => $this->getUser()]);
            $student->setCountCredit($student->getCountCredit() + $package['nbCredit']);
            $em->persist($student);
            $em->flush();


            return $this->json([
                'type'=>'success',
                'message' => 'Votre paiement à bien été effectué',
            ], 200);




        } catch (\Exception $e) {
            //throw $th;
            return $this->json([
                'message' => $e->getMessage(),
            ], 400);
        }

    }
}
