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
    *  Lists orders with optional filters by status or assignedUser.
    *  Roles allowed: Admin, Coordinator, Warehouse (they can all see lists).
    */
    #[Route('', name: 'order_list', methods: ['GET'])]
    #[IsGranted('ROLE_WAREHOUSE')]
    public function list(Request $request): JsonResponse
    {
        $status = $request->query->get('status');
        $assignedUserId = $request->query->get('assignedUserId');
        $orders = $this->orderService->getOrders($status, $assignedUserId);
        return new $this->json($orders, Response::HTTP_OK);
    }

    /**
     * Creates a new order.
     * Roles allowed: Admin, Coordinator. (Warehouse cannot create new orders)
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
     * Retrieve detail for a single order.
     * Roles allowed: Admin, Coordinator, Warehouse.
     */
    #[Route('/{id}', name: 'order_detail', methods: ['GET'])]
    #[IsGranted('ROLE_WAREHOUSE')]
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
     * Roles allowed: Admin, Coordinator can update everything.
     */
    #[Route('/{id}', name: 'order_update', methods: ['PUT'])]
    #[IsGranted('ROLE_WAREHOUSE')]
    public function update(Request $request, int $id): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$this->isGranted('ROLE_COORDINATOR') && !$this->isGranted('ROLE_ADMIN')) {
            unset($data['assignedUserId'], $data['orderNumber']);
        }

        $order = $this->orderService->updateOrder($id, $data);
        return $this->json($order ?? [], $order ? Response::HTTP_OK : Response::HTTP_NOT_FOUND);
    }

    /**
     * Deletes an existing order by ID.
     * Roles allowed: Admin or Coordinator only. (Warehouse cannot delete)
     */
    #[Route('/{id}', name: 'order_delete', methods: ['DELETE'])]
    #[IsGranted('ROLE_COORDINATOR')]
    public function delete(int $id): JsonResponse
    {
        $this->orderService->deleteOrder($id);
        return new JsonResponse([], Response::HTTP_NO_CONTENT);
    }
}
