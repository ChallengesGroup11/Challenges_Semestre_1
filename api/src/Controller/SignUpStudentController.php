<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;
use App\Entity\User;
use App\Entity\Student;
use Symfony\Component\HttpFoundation\RequestStack;


#[AsController]
class SignUpStudentController extends AbstractController
{

    public function __construct(
        private RequestStack $requestStack,
    ) {}
    //
    public function __invoke(UserRepository $userRepository,EntityManagerInterface $entityManager,UserPasswordHasherInterface $passwordHasher,MailerInterface $mailer) :JsonResponse
    {

        // TODO : Secure if not email in body

            $email = json_decode($this->requestStack->getCurrentRequest()->getContent())->email;
            $password =json_decode($this->requestStack->getCurrentRequest()->getContent())->password;
            $firstname = json_decode($this->requestStack->getCurrentRequest()->getContent())->firstname;
            $lastname = json_decode($this->requestStack->getCurrentRequest()->getContent())->lastname;
            $status = json_decode($this->requestStack->getCurrentRequest()->getContent())->status;
            $roles = json_decode($this->requestStack->getCurrentRequest()->getContent())->roles;


            $user = new User();
            $user->setEmail($email);
            $user->setPassword($passwordHasher->hashPassword($user,$password));
            $user->setFirstname($firstname);
            $user->setLastname($lastname);
            $user->setStatus($status);
            $user->setRoles($roles);
            $user->setToken(bin2hex(random_bytes(32)));


            $entityManager->persist($user);
            $entityManager->flush();

            $routeCheckAccount = "http://localhost:4010/auth/CheckAccount/". $user->getId()."?token=". $user->getToken();
            $emailBody = $this->EmailBody($routeCheckAccount);

             $email = (new Email())
                 ->from('support@drivequeen.com')
                 ->to($user->getEmail())
                 ->subject('Confirmation de votre compte')
                 ->html($emailBody);

             $mailer->send($email);




        return $this->json(['success' => 'Account created but not enabled, activate the account by clicking the link sent by email'], 201);

    }

    private function EmailBody($routeCheckAccount)
    {
        //tempalte email
        return "
        <!DOCTYPE html>
            <html lang='fr'>
            <head>
                <meta charset='UTF-8'>
                <meta http-equiv='X-UA-Compatible' content='IE=edge'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>Document</title>
            </head>
            <body>
            <style>
                h1{
                    color: #000;
                }
                p{
                    color: #000;
                }

            </style>
            <img src='https://cdn.quasar.dev/logo-v2/svg/logo-mono-white.svg' alt='logo drivequeen'>


            <h1>Confirmation de votre compte ??tudiant!</h1>
            <p>Vous avez cr???? un compte sur DriveQueen</p>
            <p>Vous devez confirmer votre compte en cliquant sur le lien ci-dessous</p>
            <a href='{$routeCheckAccount}'>Confirmer mon compte</a>

            </body>
            </html>
        ";
    }
}
