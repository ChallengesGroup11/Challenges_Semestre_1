<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpFoundation\RequestStack;
// use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository;
use App\Entity\User;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Student;

    


#[AsController]
class SignUpController extends AbstractController
{

    // Fonction construct with user
    public function __construct(
        private RequestStack $requestStack,
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
        private UserRepository $userRepository,
        private Request $request,
        private SerializerInterface $serializer

    ) {
    }


    public function __invoke()
    {

        // TODO : Secure if not email in body


        // $email = $this->serializer->deserialize($this->requestStack->getCurrentRequest()->getContent()->email, 'json');

        // $email = $this->requestStack->getCurrentRequest()->getContent()->email;
        // $email = $this->requestStack->getCurrentRequest()->getContent()->email;
        // $password = $this->requestStack->getCurrentRequest()->getContent()->password;
        // $name = $this->requestStack->getCurrentRequest()->getContent()->name;
        // $firstname = $this->requestStack->getCurrentRequest()->getContent()->firstname;
        // $lastname = $this->requestStack->getCurrentRequest()->getContent()->lastname;
        // $identity = $this->requestStack->getCurrentRequest()->getContent()->identity;
        // $email = $this->requestStack->getCurrentRequest()->getContent()->email;
        // $password = $this->requestStack->getCurrentRequest()->getContent()->password;
        // $name = $this->requestStack->getCurrentRequest()->getContent()->name;
        // $firstname = $this->requestStack->getCurrentRequest()->getContent()->firstname;
        // $lastname = $this->requestStack->getCurrentRequest()->getContent()->lastname;

        $content = json_decode($this->requestStack->getCurrentRequest()->getContent());
        dd($content);

        $email = $content->email;
        $password = $content->password;
        $name = $content->name;
        $firstname = $content->firstname;
        $lastname = $content->lastname;
        $identity = $content->identity;



        if ($identity == 'student') {
            $user = new User();
            $user->setEmail($email);
            $user->setPassword($this->passwordHasher->hashPassword($user, $password));
            $user->setFirstname($name);
            $user->setLastname($lastname);
            $user->setStatus(false);
            $user->setRoles(['ROLE_USER']);
            $user->setToken(bin2hex(random_bytes(32)));


            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $userUnique = $this->userRepository->findOneBy(['email' => $email]);

            $student = new Student();
            $student->setUserId($userUnique);
            // système file
            $student->setUrlCni('');
            $student->setUrlCodeCertification('');

            $this->entityManager->persist($student);
            $this->entityManager->flush();


            // $routeCheckAccount = "https//localhost/checkAccount/". $user->getId()."?token=". $user->getToken();
            // $emailBody = $this->EmailBody($routeCheckAccount);

            // $email = (new Email())
            //     ->from('support@drivequeen.com')
            //     ->to($user->getEmail())
            //     ->subject('Confirmation de votre compte')
            //     ->html($emailBody);

            // $mailer->send($email);




        }

        if ($identity == 'director') {
        }
    }

    private function EmailBody($routeCheckAccount)
    {
        //tempalte email 
        return `
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

            </style>
            <img src='https://drivequeen.com/wp-content/uploads/2021/05/logo-drivequeen.png' alt='logo drivequeen'>
    
                
            <h1>Confirmation de votre compte</h1>
            !<p>Vous avez créé un compte sur DriveQueen</p>
            <p>Vous devez confirmer votre compte en cliquant sur le lien ci-dessous</p>
            <a href='$routeCheckAccount'>Confirmer mon compte</a>

            </body>
            </html>
        `;
    }
}
