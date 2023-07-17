<?php

declare(strict_types=1);

namespace App\Exceptions;

use Throwable;

class Exception extends \Exception
{
    final public function __construct(
        string $message = "",
        int $code = 0,
        Throwable|null $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }

    public static function because(string $message): self
    {
        return new static($message);
    }
}
