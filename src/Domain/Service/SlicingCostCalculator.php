<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\ValueObject\Cost;
use App\Domain\ValueObject\Price;

class SlicingCostCalculator
{
    public function calculateCost(
        Price $slicingPrice,
        int $cutCount,
    ): Cost {
        $totalSlicingCost = bcmul($slicingPrice->getPrice(), "$cutCount");

        return Cost::fromString($totalSlicingCost);
    }
}
