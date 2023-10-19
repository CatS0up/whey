<?php

declare(strict_types=1);

namespace App\Enums;

enum Role: int
{
    case User = 1;
    case Trainer = 2;
    case Admin = 3;
}
