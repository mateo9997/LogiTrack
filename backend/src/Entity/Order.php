<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\ORM\Mapping as ORM;
use DateTime;
use App\Entity\User;
use App\Entity\Shipment;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: 'orders')]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 50)]
    private string $orderNumber;

    #[ORM\Column(type: 'string', length: 20)]
    private string $status = 'pending';

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'assigned_user_id', referencedColumnName: 'id', nullable: true)]
    private ?User $assignedUser = null;

    #[ORM\OneToOne(targetEntity: Shipment::class, mappedBy: 'order', cascade: ['persist', 'remove'])]
    private ?Shipment $shipment = null;

    #[ORM\Column(type: 'datetime')]
    private DateTime $createdAt;

    #[ORM\Column(type: 'datetime')]
    private DateTime $updatedAt;

    public function __construct()
    {
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getOrderNumber(): string
    {
        return $this->orderNumber;
    }

    public function setOrderNumber(string $orderNumber): self
    {
        $this->orderNumber = $orderNumber;
        return $this->markUpdated();
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this->markUpdated();
    }

    public function getAssignedUser(): ?User
    {
        return $this->assignedUser;
    }

    public function setAssignedUser(?User $user): self
    {
        $this->assignedUser = $user;
        return $this->markUpdated();
    }

    public function assignUser(User $user): self
    {
        $this->assignedUser = $user;
        return $this->markUpdated();
    }

    public function getShipment(): ?Shipment
    {
        return $this->shipment;
    }

    public function setShipment(?Shipment $shipment): self
    {
        $this->shipment = $shipment;

        if ($shipment !== null) {
            $shipment->setOrder($this);
        }

        return $this->markUpdated();
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }

    private function markUpdated(): self
    {
        $this->updatedAt = new DateTime();
        return $this;
    }
}
