<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

use App\Domain\Enum\RegionCodeEnum;
use InvalidArgumentException;

readonly class Region
{
    private int $regionCode;

    public function __construct(
        int $regionCode
    ) {
        $this->assertRegionCode($regionCode);
        $this->regionCode = $regionCode;
    }

    public function getRegionCode(): int
    {
        return $this->regionCode;
    }

    public function isSame(Region $region): bool
    {
        return $this->regionCode === $region->getRegionCode();
    }

    private function assertRegionCode(int $regionCode): void
    {
        if (!in_array($regionCode, RegionCodeEnum::cases(), true)) {
            throw new InvalidArgumentException('Неизвестный регион ' . $regionCode);
        }
    }
}
