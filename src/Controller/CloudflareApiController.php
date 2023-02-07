<?php

namespace Akyos\CloudflareapiBundle\Controller;

use Akyos\CloudflareapiBundle\Service\CloudflareService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/cloudflare/api', name: 'cloudflare_api_')]
class CloudflareApiController extends AbstractController
{
    #[Route('/patch/{element}', name: 'patch')]
    public function patch(string $element, Request $request, CloudflareService $cloudflareService): JsonResponse
    {
        $values = $request->getContent();
        return $cloudflareService->setValue($values, $element);
    }
}
