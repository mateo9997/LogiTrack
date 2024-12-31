<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ShipmentRepository;
use DateTime;

#[ORM\Entity(repositoryClass: ShipmentRepository::class)]
class Shipment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:"integer")]
    private int $id;

    #[ORM\Column(type:"string", length:100)]
    private string $carrierName;

    #[ORM\Column(type:"string", length:100)]
    private string $trackingNumber;

    #[ORM\Column(type:"datetime", nullable:true)]
    private ?DateTime $estimatedDelivery;

    #[ORM\OneToOne(inversedBy:"shipment", targetEntity:"App\Entity\Order")]
    #[ORM\JoinColumn(nullable:false)]
    private Order $order;

    public function getId(): int
    {
        return $this->id;
    }

    public function getCarrierName(): string
    {
        return $this->carrierName;
    }
    public function setCarrierName(string $carrierName): self
    {
        $this->carrierName = $carrierName;
        return $this;
    }

    public function getTrackingNumber(): string
    {
        return $this->trackingNumber;
    }
    public function setTrackingNumber(string $trackingNumber): self
    {
        $this->trackingNumber = $trackingNumber;
        return $this;
    }

    public function getEstimatedDelivery(): ?DateTime
    {
        return $this->estimatedDelivery;
    }
    public function setEstimatedDelivery(?DateTime $date): self
    {
        $this->estimatedDelivery = $date;
        return $this;
    }

    public function getOrder(): Order
    {
        return $this->order;
    }
    public function setOrder(Order $order): self
    {
        $this->order = $order;
        return $this;
    }
}
