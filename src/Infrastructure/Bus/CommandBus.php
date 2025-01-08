<?php

declare(strict_types=1);

namespace App\Infrastructure\Bus;

use App\Application\Command\CommandBusInterface;
use App\Application\Command\CommandInterface;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

class CommandBus implements CommandBusInterface
{
    use HandleTrait;

    public function __construct(
        private MessageBusInterface $commandBus,
    ) {
    }

    public function execute(CommandInterface $command): mixed
    {
        return $this->handle($command);
    }
}
