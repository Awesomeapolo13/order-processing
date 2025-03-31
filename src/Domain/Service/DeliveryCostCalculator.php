<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\ValueObject\Cost;
use App\Domain\ValueObject\Distance;

class DeliveryCostCalculator
{
    private const string FREE_DELIVERY_COST_THRESHOLD = '1500.00';
    private const string DEFAULT_DELIVERY_COST = '200.00';
    private const string LONG_DURATION_COST = '200.00';
    private const string LONG_DISTANCE_PER_KM_COST = '100.00';

    public function calculateDeliveryFactCost(Distance $distance): Cost
    {
        $cost = $this->getDefaultDeliveryCost();

        if ($distance->isLongDistance()) {
            $cost = $cost->add($this->getRemainingLongDistanceCost($distance));
        }

        if ($distance->isLongDuration()) {
            $cost = $cost->add($this->getLongDurationCost());
        }

        return $cost;
    }

    public function calculateDeliveryCostWithDiscount(Cost $totalDiscountCost, Distance $distance): Cost
    {
        $cost = Cost::zero();
        $freeDeliveryThresholdCost = Cost::fromString(self::FREE_DELIVERY_COST_THRESHOLD);

        if (
            !$distance->isLongDistance()
            && !$distance->isLongDuration()
            && ($totalDiscountCost->moreThan($freeDeliveryThresholdCost) || $totalDiscountCost->equals($freeDeliveryThresholdCost))
        ) {
            return $cost;
        }

        if ($totalDiscountCost->lessThan($freeDeliveryThresholdCost)) {
            $cost = $this->getDefaultDeliveryCost();
        }

        if ($distance->isLongDistance()) {
            $cost = $cost->add($this->getRemainingLongDistanceCost($distance));
        }

        if ($distance->isLongDuration()) {
            $cost = $cost->add($this->getLongDurationCost());
        }

        return $cost;
    }

    private function getDefaultDeliveryCost(): Cost
    {
        return Cost::fromString(self::DEFAULT_DELIVERY_COST);
    }

    private function getLongDurationCost(): Cost
    {
        return Cost::fromString(self::LONG_DURATION_COST);
    }

    private function getRemainingLongDistanceCost(Distance $distance): Cost
    {
        $remainingDistance = bcsub($distance->getDistance(), (string)Distance::LONG_DISTANCE, 0);

        return Cost::fromString(bcmul($remainingDistance, self::LONG_DISTANCE_PER_KM_COST));
    }
}
