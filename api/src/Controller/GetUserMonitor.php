<?php

namespace App\Controller;

use App\Repository\MonitorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Serializer\SerializerInterface;

#[asController]
class GetUserMonitor extends AbstractController
{
    public function __invoke( Request $request , SerializerInterface $serializer, MonitorRepository $monitorRepository) : JsonResponse
    {
        $user = $monitorRepository->findAllMonitor();
        return $this->json($user, 201, ['success']);
    }

}
