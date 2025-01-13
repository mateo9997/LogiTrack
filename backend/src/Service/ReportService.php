<?php

namespace App\Service;

use App\Repository\OrderRepository;

class ReportService
{
    public function __construct(private OrderRepository $orderRepository){}

    /**
     * Returns overview of total orders, counts by status.
     */
    public function getOrderOverview(): array
    {
        $orders = $this->orderRepository->findAll();
        $overview = [
            'totalOrders' => count($orders),
            'pending'=> 0,
            'shipped'=> 0,
            'delivered'=> 0,
        ];

        foreach ($orders as $order) {
            $status = $order->getStatus();
            if(isset($overview[$status])) {
                $overview[$status]++;
            }
        }
        return $overview;
    }

    /**
     * Calculate fulfillment rates, etc.
     */
    public function getFulfillmentRates(): array
    {
        $orders = $this->orderRepository->findAll();
        $total = count($orders);
        $delivered = 0;

        foreach ($orders as $order) {
            if($order->getStatus() === 'delivered') {
                $delivered++;
            }
        }

        $fulfillmentRate = ($total > 0)
            ? round(($delivered / $total) * 100, 2)
            : 0;

        return [
            'fulfillmentRate' => $fulfillmentRate,
        ];
    }

}