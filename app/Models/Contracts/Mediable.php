<?php

declare(strict_types=1);

namespace App\Models\Contracts;

interface Mediable
{
    public function id(): int;
    public function type(): string;
    public function getSubDirectoryFilePath(): string;
}
