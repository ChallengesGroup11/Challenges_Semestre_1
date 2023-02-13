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
use App\Repository\MonitorRepository;
use App\Entity\User;
use App\Entity\Monitor;
use Symfony\Component\HttpFoundation\RequestStack;


#[AsController]
class DeleteMonitorController extends AbstractController
{

    public function __construct(
        private RequestStack $requestStack,
    ) {}
//MailerInterface $mailer
    public function __invoke(MailerInterface $mailer,UserRepository $userRepository,MonitorRepository $monitorRepository, EntityManagerInterface $entityManager,UserPasswordHasherInterface $passwordHasher ) :JsonResponse
    {
        $id = json_decode($this->requestStack->getCurrentRequest()->getContent())->id;
        $monitor = $monitorRepository->findOneBy(['userId' => $id]);
        $entityManager->remove($monitor);
        $entityManager->flush();


        $user = $userRepository->findOneBy(['monitor' => $monitor]);
        $entityManager->remove($user);
        $entityManager->flush();
        
        return new JsonResponse(['message' => 'Monitor deleted'], 204);

    }
}
