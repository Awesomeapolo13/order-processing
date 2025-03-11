<?php

declare(strict_types=1);

namespace App\Application\Assembler;

/**
 * @template TEntity
 * @template TResponse
 */
interface ResponseAssemblerInterface
{
    /**
     * @param TEntity $entity
     * @return TResponse
     */
    public function createResponse(object $entity): object;
}
