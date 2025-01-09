<?php

namespace App\Controller;

use App\Service\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
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

    /**
     * Creates a new order with optional assignedUserId and shipment data.
     * Expects JSON payload: { "orderNumber": "...", "status": "...", "assignedUserId":..., "shipment": {...} }
     */
    #[Route('', name: 'order_create', methods: ['POST'])]
    #[IsGranted('ROLE_COORDINATOR')]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $order = $this->orderService->createOrder($data);
        return new JsonResponse($order, Response::HTTP_CREATED);
    }

    /**
     * Retrieves details of a single order by ID.
     */
    #[Route('/{id}', name: 'order_detail', methods: ['GET'])]
    #[IsGranted('ROLE_COORDINATOR')]
    public function detail(string $id): JsonResponse
    {
        $order = $this->orderService->getOrder($id);
        if (!$order) {
            return $this->json(['error' => 'Order not found'], Response::HTTP_NOT_FOUND);
        }
        return $this->json($order, Response::HTTP_OK);
    }

    /**
     * Updates an existing order.
     * JSON payload can include { "status": "...", "assignedUserId":..., "shipment": {...}, ... }
     */
    #[Route('/{id}', name: 'order_update', methods: ['PUT'])]
    #[IsGranted('ROLE_COORDINATOR')]
    public function update(Request $request, int $id): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $order = $this->orderService->updateOrder($id, $data);
        if (!$order) {
            return $this->json(['error' => 'Order not found'], Response::HTTP_NOT_FOUND);
        }
        return $this->json($order, Response::HTTP_OK);
    }

    /**
     * Deletes an existing order by ID.
     */
    #[Route('/{id}', name: 'order_delete', methods: ['DELETE'])]
    #[IsGranted('ROLE_COORDINATOR')]
    public function delete(int $id): JsonResponse
    {
        $this->orderService->deleteOrder($id);
        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }

}
