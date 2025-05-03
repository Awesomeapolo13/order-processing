<?php

declare(strict_types=1);

namespace App\Infrastructure\Service\Validation\Constraint\OrderDate;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class OrderDateOnlyInFutureValidator extends ConstraintValidator
{
    /**
     * @param OrderDateOnlyInFuture $constraint
     *
     * @throws \DateMalformedStringException
     */
    public function validate(mixed $value, Constraint $constraint): void
    {
        $date = new \DateTime($value);
        $now = new \DateTime();

        if ($date < $now) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->addViolation();
        }
    }
}
