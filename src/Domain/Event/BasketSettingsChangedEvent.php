<?php

declare(strict_types=1);

namespace App\Domain\Event;

use App\Domain\Event\EventInterface;

readonly class BasketSettingsChangedEvent implements EventInterface
{
    public function __construct(
        public int $userId,
        public int $region,
    ) {
    }
}
