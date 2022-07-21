<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;

class UploadsAjaxController extends AbstractController
{
    public function __construct(
        private readonly KernelInterface $kernel,
    ) {}

    public function uploadImg(Request $request): JsonResponse
    {
            $file = $request->files->get('file');
            $dir = $this->kernel->getProjectDir() . '/public/uploads/';
            $extension = $file->guessExtension();
            $filename = date('Y-m-d_H:i:s_') . rand(1, 99999) . "." . $extension;
            $file->move($dir, $filename);

            return $this->json($filename);
    }
}