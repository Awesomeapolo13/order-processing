<?php

declare(strict_types=1);

namespace App\Application\Event;

use App\Domain\Event\EventInterface;

interface EventBusInterface
{
    public function execute(EventInterface ...$events): void;
}
