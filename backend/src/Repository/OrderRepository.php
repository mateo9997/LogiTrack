<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }
    public function findByFilters(?string $status, ?string $assignedUser): array
    {
        $qb = $this->createQueryBuilder('o');

        if ($status) {
            $qb->andWhere('o.status = :status')->setParameter('status', $status);
        }

        if ($assignedUser) {
            $qb->andWhere('o.assignedUser = :assignedUser')->setParamater('assignedUser', $assignedUser);
        }

        return $qb->getQuery()->getResult();
    }
}