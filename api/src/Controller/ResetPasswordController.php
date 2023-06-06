<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsController]
class ResetPasswordController extends AbstractController
{
    public function __construct(
        private RequestStack $requestStack,
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepository,
        private MailerInterface $mailer,
        private UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function __invoke()
    {

        // TODO : Secure if not email in body

        $password = json_decode($this->requestStack->getCurrentRequest()->getContent())->password;
        $userId = json_decode($this->requestStack->getCurrentRequest()->getContent())->userId;
        $token = json_decode($this->requestStack->getCurrentRequest()->getContent())->token;
        $user = $this->userRepository->findOneBy(['id' => $userId]);
        if ($user->getToken() === $token) {
            $user->setPassword($this->passwordHasher->hashPassword($user,$password));
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $emailBody = $this->EmailBody();
            // TODO : send email
            $email = (new Email())
                ->from('support@drivequeen.com')
                ->to($user->getEmail())
                ->subject('Modification du mot de passe')
                ->html($emailBody);

            $this->mailer->send($email);
            return $this->json('Success');
        }else{
            return $this->json(['error' => 'Token invalide'], 510);
        }
    }


    private function EmailBody()
    {
        //tempalte email
        return "
        <!doctype html>
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
                a{
                    background-color: #000;
                    color: #fff;
                    padding: 10px 20px;
                    border-radius: 5px;
                    text-decoration: none;
                }
            </style>
           <img src='https://cdn.quasar.dev/logo-v2/svg/logo-mono-white.svg' alt='logo drivequeen' width='100' height='100'>


            <h1>Modification de mot de passe</h1>
            <p>Votre mot de passe à bien été modifié </p>

            </body>
            </html>
        ";
    }
}
