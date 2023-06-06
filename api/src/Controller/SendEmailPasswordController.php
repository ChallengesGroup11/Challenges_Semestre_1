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

#[AsController]
class SendEmailPasswordController extends AbstractController
{
    public function __construct(
        private RequestStack $requestStack,
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepository,
        private MailerInterface $mailer
    ) {
    }

    public function __invoke()
    {
        // TODO : Secure if not email in body
        $email = json_decode($this->requestStack->getCurrentRequest()->getContent())->email;
        $user = $this->userRepository->findOneBy(['email' => $email]);

        if(!$user){
            return $this->json(['error' => 'Aucun compte n\'est associé à cet email'], 510);

        }

        $user->setToken(bin2hex(random_bytes(32)));
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        if($_ENV['APP_ENV'] == 'dev'){
            $routeChangeMdp = "http://localhost:4010/auth/ChangeMdp/" . $user->getId() . "?token=" . $user->getToken();
        }else{
            $routeChangeMdp = "https://drive-queen.turtletv.fr/auth/ChangeMdp/" . $user->getId() . "?token=" . $user->getToken();
        }
        // $routeChangeMdp = "http://localhost:4010/auth/ChangeMdp/" . $user->getId() . "?token=" . $user->getToken();
        $emailBody = $this->EmailBody($routeChangeMdp);
        // TODO : send email
        $email = (new Email())
            ->from('support@drivequeen.com')
            ->to($user->getEmail())
            ->subject('Modification de mot de passe')
            ->html($emailBody);

        $this->mailer->send($email);
        return $this->json('Success');
    }


    private function EmailBody($routeChangeMdp)
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
            <p>Veuillez vous rendre sous le lien ci dessous pour modifier votre mot de passe </p>
            <a href='{$routeChangeMdp}'>Confirmer mon compte</a>

            </body>
            </html>
        ";
    }
}
