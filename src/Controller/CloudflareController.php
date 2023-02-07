<?php

namespace Akyos\CloudflareapiBundle\Controller;

use Akyos\CloudflareapiBundle\Service\CloudflareService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/cloudflare', name: 'admin_cloudflare_')]
class CloudflareController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(CloudflareService $cloudflareService): Response
    {
        $values = [];
        foreach (CloudflareService::API_URI as $key => $el) {
            if ($el['type'] != 'button') {
                $values[$key] = [
                    'value' => $cloudflareService->getValue($el['uri']),
                    'label' => $el['label'],
                    'help' => $el['help'],
                    'type' => $el['type'],
                ];
            } else {
                $values[$key] = [
                    'label' => $el['label'],
                    'help' => $el['help'],
                    'type' => $el['type'],
                ];
            }
        }
        return $this->render('@Akyos/CloudflareAPIBundle/index.html.twig', [
            'values' => $values
        ]);
    }
}
