<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

interface ShopInterface
{
    public function getNumber(): int;

    public function getRegion(): Region;

    public function getOpenDateTime(): ?\DateTimeImmutable;

    public function getCloseDateTime(): ?\DateTimeImmutable;
}
