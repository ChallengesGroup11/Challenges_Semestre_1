<?php


namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;

use App\Entity\User;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class CheckAccountController extends AbstractController
{

    public function __construct(
        private RequestStack $requestStack,
    ) {}

    public function __invoke(EntityManagerInterface $entityManager, UserRepository $userRepository)
    {

        $userId = json_decode($this->requestStack->getCurrentRequest()->getContent())->userId;
        $token = json_decode($this->requestStack->getCurrentRequest()->getContent())->token;
        $user = $userRepository->findOneBy(['id' => $userId]);
        if($user->getToken() === $token){
            $user->setStatus('active');
            $entityManager->persist($user);
            $entityManager->flush();
            return $this->json(['type'=>'success','message' => 'Votre compte est activé']);
        }else{
            return $this->json(['type'=>'error','message' => 'Token non valide']);
        }

        return $this->json(['type'=>'error','message' => 'Un problème est survenu']);


    }

}
