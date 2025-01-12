<?php

declare(strict_types=1);

namespace App\Infrastructure\Database\EntityManager;

use App\Application\Database\EntityManager\TransactionalEntityManagerInterface;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface as DoctrineEntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;

class DoctrineEntityManager implements TransactionalEntityManagerInterface
{
    public function __construct(
        private readonly DoctrineEntityManagerInterface $entityManager,
    ) {
    }

    public function beginTransaction(): void
    {
        $this->entityManager->beginTransaction();
    }

    /**
     * @throws OptimisticLockException
     */
    public function lock(object $entity, int $lockMode, DateTimeInterface|int|null $lockVersion = null): void
    {
        $this->entityManager->lock($entity, $lockMode, $lockVersion);
    }

    public function persist(object $entity): void
    {
        $this->entityManager->persist($entity);
    }

    public function flush(): void
    {
        $this->entityManager->flush();
    }

    public function commit(): void
    {
        $this->entityManager->commit();
    }

    public function rollback(): void
    {
        $this->entityManager->rollback();
    }
}
