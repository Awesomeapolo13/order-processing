<?php

declare(strict_types=1);

namespace App\Application\Database\Enum;

enum LockModeEnum: int
{
    case NONE = 0;
    case OPTIMISTIC = 1;
    case PESSIMISTIC_READ = 2;
    case PESSIMISTIC_WRITE = 4;
}
