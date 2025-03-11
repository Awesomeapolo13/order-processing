<?php

declare(strict_types=1);

namespace App\Domain\Enum;

enum ProductType
{
    case PIECE;
    case WEIGHT;
    case MIXED;
}
