<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Application\Event\EventBusInterface;
use App\Domain\Entity\Basket;
use App\Domain\Repository\BasketRepositoryInterface;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Basket>
 */
class DoctrineBasketRepository extends ServiceEntityRepository implements BasketRepositoryInterface
{
    public function __construct(
        private readonly EventBusInterface $eventBus,
        ManagerRegistry $registry
    ) {
        parent::__construct($registry, Basket::class);
    }

    public function findActiveBasketByUserId(int $userId): ?Basket
    {
        return $this
            ->getEntityManager()
            ->createQueryBuilder()
            ->select('b')
            ->from(Basket::class, 'b')
            ->addSelect('d', 'bi')
            ->leftJoin('b.delivery', 'd')
            ->leftJoin('b.basketItems', 'bi')
            ->where('b.userId = :userId')
            ->andWhere('b.deletedAt IS NULL')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function setBasketDeleted(int $userId): void
    {
        $this
            ->getEntityManager()
            ->createQuery(
                'UPDATE App\Domain\Entity\Basket b'
                . ' SET b.deletedAt = :now'
                . ' WHERE b.userId = :userId AND b.deletedAt IS NULL'
            )
            ->setParameters([
                'now' => new DateTimeImmutable(),
                'userId' => $userId
            ])
            ->execute();
    }

    public function save(Basket $basket): void
    {
        $this->getEntityManager()->persist($basket);
        $this->getEntityManager()->flush();
        $this->eventBus->execute($basket->releaseEvents());
    }
}
