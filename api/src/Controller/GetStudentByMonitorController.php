<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\MonitorRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\SerializerInterface;


#[AsController]
class GetStudentByMonitorController extends AbstractController
{

    public function __invoke(MonitorRepository $monitorRepository, Request $request) : Response
    {

        $student = $monitorRepository->findStudentByMonitor($request->get('id'));


        return $this->json($student, 200, [], ['groups' => ['monitor_cget']]);

    }
}
