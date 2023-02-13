<?php

namespace App\Controller;

use App\Entity\Monitor;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Serializer\SerializerInterface;

#[asController]
class MonitorEditStatusController extends AbstractController
{
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    public function __invoke(Monitor $monitor, Request $request , SerializerInterface $serializer ): JsonResponse
    {
        $monitor = $serializer->deserialize($request->getContent(), Monitor::class, 'json', ['object_to_populate' => $monitor]);
        $monitor->setStatus(!$monitor->getStatus());
        $this->em->persist($monitor);
        $this->em->flush();

        return $this->json($monitor, 200, ['success']);
    }



}
