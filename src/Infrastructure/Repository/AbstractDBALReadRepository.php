<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;

abstract class AbstractDBALReadRepository
{
    public function __construct(
        protected EntityManagerInterface $entityManager,
    ) {
    }

    protected function getConnection(): Connection
    {
        return $this->entityManager->getConnection();
    }
}
