<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class Ping extends AbstractController
{
    public function __construct(
    ) {}
    public function __invoke()
    {


            return $this->json([
                'type'=>'success',
                'message' => 'pong',
            ], 200);

    }
}
