<?php

namespace App\Controller;

use App\Service\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api/orders')]
class OrderController extends AbstractController
{
    public function __construct(private OrderService $orderService)
    {
    }

   /**
    * Lists orders, with optional filters: ?status=... & ?assignedUserId=...
    */
    #[Route('', name: 'order_list', methods: ['GET'])]
    #[IsGranted('ROLE_COORDINATOR')]
    public function list(Request $request): JsonResponse
    {
        $status = $request->query->get('status');
        $assignedUserId = $request->query->get('assignedUserId');
        $orders = $this->orderService->getOrders($status, $assignedUserId);
        return new $this->json($orders, Response::HTTP_OK);
    }
}
