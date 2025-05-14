<?php

declare(strict_types=1);

namespace App\Application\Database\EntityManager;

interface TransactionalEntityManagerInterface
{
    public function beginTransaction(): void;

    public function lock(object $entity, int $lockMode, \DateTimeInterface|int|null $lockVersion = null): void;

    public function persist(object $entity): void;

    public function flush(): void;

    public function commit(): void;

    public function rollback(): void;
}
