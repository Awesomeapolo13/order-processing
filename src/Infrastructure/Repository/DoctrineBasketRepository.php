<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Entity\Basket;
use App\Domain\Repository\BasketRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Basket>
 */
class DoctrineBasketRepository extends ServiceEntityRepository implements BasketRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Basket::class);
    }

    public function findBasketByUserId(int $userId): ?Basket
    {
        return $this
            ->createQueryBuilder('b')
            ->addSelect('d', 'bi')
            ->leftJoin('b.delivery', 'd')
            ->leftJoin('b.basketItems', 'bi')
            ->where('b.userId = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getSingleResult();
    }
}
