<?php

declare(strict_types=1);

namespace App\Infrastructure\Service\Validation\Constraint\OrderDate;

use Symfony\Component\Validator\Constraint;

class OrderDateOnlyInFuture extends Constraint
{
    public string $message = 'Order date can not be in the past. Got {{ value }}.';
}
