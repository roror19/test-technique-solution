<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/file-update', name: 'file_update')]
class FileController extends AbstractController
{
    public function __construct(protected ?KernelInterface $kernel) { }

    #[Route(path: '', name: '')]
    public function write(Request $request): JsonResponse
    {
        $response = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $file = $this->kernel->getProjectDir() . '/public/chrono.txt';

        if (file_exists($file)) {
            file_put_contents($file, $response['hrs'] . ' : ' . $response['min'] . ' : ' . $response['sec']);
        }

        return new JsonResponse('success', 200);
    }

    #[Route(path: '-check', name: '_check')]
    public function check(Request $request): JsonResponse
    {
        $file = $this->kernel->getProjectDir() . '/public/chrono.txt';

        $fileContent = file_get_contents($file);

        if (empty($fileContent)) {
            return new JsonResponse('notStarted', 200);
        }

        $times = explode(' : ', $fileContent);

        [$hrs, $min, $sec] = $times;

        return new JsonResponse([
            'hrs' => $hrs,
            'min' => $min,
            'sec' => $sec,
        ], 200);
    }
}