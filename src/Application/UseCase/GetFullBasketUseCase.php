<?php

declare(strict_types=1);

namespace App\Application\UseCase;

use App\Application\Response\DeliveryResponse;
use App\Application\Response\GetFullBasketResponse;

class GetFullBasketUseCase
{
    public function __invoke(): GetFullBasketResponse
    {
        return new GetFullBasketResponse(
            1,
            true,
            true,
            new \DateTime(),
            '',
            '',
            '',
            '',
            0,
            new DeliveryResponse(
                0,
                false,
                '',
                '',
                '',
            ),
            [
            ]
        );
    }
}
