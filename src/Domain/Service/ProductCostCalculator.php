<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\ValueObject\Cost;
use App\Domain\ValueObject\Price;
use App\Domain\ValueObject\ProductQuantity;
use App\Domain\ValueObject\Weight;
use InvalidArgumentException;

class ProductCostCalculator
{
    /**
     * The number of decimal places for money calculation.
     * Store more places for middle calculations to avoid errors with rounding.
     */
    private const COST_CALC_SCALE = 6;
    private const WEIGHT_CALC_SCALE = 3;
    private const RESULT_SCALE = 2;

    public function calculateCost(
        Price $priceByQuant,
        ProductQuantity $quantity,
        ?Weight $weightQuant,
        ?Weight $averageWeight
    ): Cost {
        if ($weightQuant === null && ($quantity->isWeight() || $quantity->isMixed())) {
            throw new InvalidArgumentException('Weight of one quant of the product need to be provided for weight product');
        }

        if ($averageWeight === null && $quantity->isMixed()) {
            throw new InvalidArgumentException(
                'Average weight must be provided for mixed type products'
            );
        }

        $result = match(true) {
            // Для штучного товара: цена * количество
            $quantity->isPiece() => $this->calculateForPiece($priceByQuant, $quantity->getQuantity()),
            // Для весового товара: цена * количество квантов
            $quantity->isWeight() => $this->calculateForWeight($priceByQuant, $quantity->getWeight(), $weightQuant),
            // Для смешанного типа: цена * количество * вес
            $quantity->isMixed() => $this->calculateForMixed($priceByQuant, $quantity->getQuantity(), $weightQuant),
        };

        // Округляем результат до 2 знаков после запятой для финального представления
        $roundedResult = bcadd($result, '0', self::RESULT_SCALE);

        return new Cost($roundedResult);
    }

    private function calculateForPiece(Price $priceByQuant, int $totalQuantity): string
    {
        return bcmul(
            $priceByQuant->getPrice(),
            (string)$totalQuantity,
            self::COST_CALC_SCALE
        );
    }

    private function calculateForWeight(Price $priceByQuant, Weight $totalWeight, Weight $weightQuant): string
    {
        $quantity = $this->getWeightQuantity($weightQuant, $totalWeight);

        return bcmul(
            $priceByQuant->getPrice(),
            $quantity,
            self::COST_CALC_SCALE
        );
    }

    private function getWeightQuantity(Weight $weightQuant, Weight $totalWeight): string
    {
        return bcdiv($totalWeight->getWeight(), $weightQuant->getWeight(), 0);
    }

    private function calculateForMixed(Price $priceByQuant, int $totalQuantity, Weight $averageWeight): string
    {
        return bcmul(
            bcmul(
                (string)$totalQuantity,
                $averageWeight->getWeight(),
                self::COST_CALC_SCALE
            ),
            $priceByQuant->getPrice(),
            self::COST_CALC_SCALE
        );
    }
}
