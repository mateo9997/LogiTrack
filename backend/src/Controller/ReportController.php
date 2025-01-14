<?php

namespace App\Controller;

use App\Service\ReportService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/reports')]
class ReportController extends AbstractController
{
    public function __construct(private ReportService $reportService) {}

    /**
     * Return an overview of total orders, status counts.
     */
    #[Route('/overview', name: 'report_overview', methods: ['GET'])]
    #[IsGranted("ROLE_WAREHOUSE")]
    public function overview(): JsonResponse
    {
        $reportData = $this->reportService->getOrderOverview();
        return $this->json($reportData, Response::HTTP_OK);
    }

    /**
     * Return fulfillment rates (delivered vs total).
     */
    #[route('/fulfillment', name: 'report_fulfillment', methods: ['GET'])]
    #[IsGranted("ROLE_WAREHOUSE")]
    public function fulfillmentRates(): JsonResponse
    {
        $data = $this->reportService->getFulfillmentRates();
        return $this->json($data, Response::HTTP_OK);
    }


}
