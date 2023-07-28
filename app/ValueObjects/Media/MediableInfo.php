<?php

declare(strict_types=1);

namespace App\ValueObjects\Media;

use InvalidArgumentException;

final class MediableInfo
{
    public const MIN_ID_VALUE = 1;

    public function __construct(
        public readonly int $id,
        public readonly string $type,
    ) {
        if ($id < self::MIN_ID_VALUE) {
            throw new InvalidArgumentException('ID of mediable model cannot be less than '.self::MIN_ID_VALUE);
        }

        if ( ! class_exists($type)) {
            throw new InvalidArgumentException("{$type} model does not exists");
        }
    }

    public function getSubDirectoryFilePath(): string
    {
        $modelName = str(class_basename($this->type))->lower();

        return "{$modelName}/{$this->id}";
    }
}
