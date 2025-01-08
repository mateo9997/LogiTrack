<?php

namespace App\Service;

use App\Entity\Order;
use App\Entity\Shipment;
use App\Repository\OrderRepository;
use App\Repository\ShipmentRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;
use Doctrine\ORM\Tools\Pagination\Paginator;

class OrderService
{
    private OrderRepository $orderRepo;
    private UserRepository $userRepo;
    private ShipmentRepository $shipmentRepo;
    private EntityManagerInterface $em;

    public function __construct(
        OrderRepository $orderRepo,
        EntityManagerInterface $em,
        UserRepository $userRepo,
        ShipmentRepository $shipmentRepo
    ) {
        $this->orderRepo = $orderRepo;
        $this->userRepo = $userRepo;
        $this->shipmentRepo = $shipmentRepo;
        $this->em = $em;
    }

    /**
     * Creates a new Order with optional user assignment and shipment data.
     *
     * @param array $data - associative array with keys like:
     *                      ['orderNumber', 'status', 'assignedUserId', 'shipment' => [...]]
     */
    public function createOrder(array $data): Order
    {
        $order= new Order();

        if(isset($data['orderNumber'])) {
            $order->setOrderNumber($data['orderNumber']);
        }
        if(isset($data['status'])) {
            $order->setStatus($data['status']);
        }

        if(!empty($data['assignedUserId'])){
            $user = $this->userRepo->find($data['assignedUserId']);
            if($user instanceof User){
                $order->setAssignedUser($user);
            }
        }

        if(!empty($data['shipment'])){
            $shipmentData = $data['shipment'];
            $shipment = new Shipment();
            $shipment->setCarrierName($shipmentData['carrierName'] ?? '');
            $shipment->setTrackingNumber($shipmentData['trackingNumber'] ?? '');

            if (!empty($shipmentData['estimatedDelivery'])) {
                $shipment->setEstimatedDelivery(new DateTime($shipmentData['estimatedDelivery']));
            }
            $order->setShipment($shipment);
        }
        $this->em->persist($order);
        $this->em->flush();
        return $order;
    }

    /**
     * Returns a list of orders, optionally filtered by status or assignedUserId.
     */
    public function getOrders(?string $status = null, ?int $assignedUserId = null): array
    {
        return $this->orderRepo->findByFilters($status, $assignedUserId);
    }

    /**
     * Retrieves a single order by ID.
     */
    public function getOrder(int $id): ?Order
    {
        return $this->orderRepo->find($id);
    }

    /**
     * Updates an existing order with data (status, assignedUser, shipment).
     */
    public function updateOrder(int $id, array $data): ?Order{
        $order = $this->getOrder($id);
        if(!$order){
            return null;
        }

        if(isset($data['orderNumber'])){
            $order->setOrderNumber($data['orderNumber']);
        }

        if(isset($data['status'])){
            $order->setStatus($data['status']);
        }

        if(array_key_exists('assignedUserId', $data)){
            $userId = $data['assignedUserId'];
            if($userId){
                $user = $this->userRepo->find($userId);
                $order->setAssignedUser($user ?: null);
            } else{
                $order->setAssignedUser(null);
            }
        }

        if(isset($data['shipment'])){
            $shipmentData = $data['shipment'];
            $shipment = $order->getShipment();
            if(!$shipment){
                $shipment = new Shipment();
                $order->setShipment($shipment);
            }
            if(isset($shipmentData['carrierName'])){
                $shipment->setCarrierName($shipmentData['carrierName']);
            }
            if (isset($shipmentData['estimatedDelivery'])) {
                $shipment->setEstimatedDelivery($shipmentData['estimatedDelivery']);
            }
            if(isset($shipmentData['trackingNumber'])){
                $shipment->setTrackingNumber($shipmentData['trackingNumber']);
            }
        }

        $this->em->flush();
        return $order;
    }

    /**
     * Deletes an existing order by ID.
     */
    public function deleteOrder(int $id): void
    {
        $order = $this->getOrder($id);
        if($order){
            $this->em->remove($order);
            $this->em->flush();
        }
    }
}