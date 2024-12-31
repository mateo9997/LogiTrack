<?php

namespace App\Service;

use App\Entity\Order;
use App\Repository\OrderRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;

class OrderService
{
    private OrderRepository $orderRepo;
    private UserRepository $userRepo;

    public function __construct(private OrderRepository $orderRepository, private EntityManagerInterface $em)
    {
    }

    public function getOrders(?string $status, ?string $assignedUser){
        return $this->orderRepository->findByFilters($status, $assignedUser);
    }
    public function getOrderDetail(int $id): ?Order
    {
        return $this->orderRepository->find($id);
    }

    public function createOrder(array $data):  Order
    {
        $order = new Order();
        $order->setOrderNumber()
    }
}