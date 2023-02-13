<?php

namespace App\Controller;

use App\Entity\Student;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

#[AsController]
class StudentPostController extends AbstractController
{
    public function __invoke(Request $request, UserRepository $userRepository): Student
    {
        $uploadedFileCode = $request->files->get('fileCode');
        $uploadedFileCni = $request->files->get('fileCni');
        if (!$uploadedFileCni || !$uploadedFileCode) {
            throw new BadRequestHttpException('"file" is required');
        }
        $student = new Student();
        $student->fileCode = $uploadedFileCode;
        $student->fileCni = $uploadedFileCni;
        $user = $userRepository->find($request->get('userId'));
        $student->setUserId($user);

        return $student;
    }
}
