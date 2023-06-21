<?php

declare(strict_types=1);

namespace App\Exceptions;

class Exception extends \Exception
{
    public static function because(string $message): self
    {
        return new static($message);
    }
}
