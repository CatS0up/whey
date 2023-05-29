<?php

declare(strict_types=1);

namespace App\ValueObjects\Media;

final class Dimension
{
    public const MAX_DIMENSION_PX_WIDTH = 1;
    public const MAX_DIMENSION_PX_HEIGHT = 1;

    public function __construct(
        public readonly int $width,
        public readonly int $height,
    ) {
        if ($width < self::MAX_DIMENSION_PX_WIDTH) {
            throw new \InvalidArgumentException('Width should be greater or equal to ' . self::MAX_DIMENSION_PX_WIDTH);
        }

        if ($height < self::MAX_DIMENSION_PX_HEIGHT) {
            throw new \InvalidArgumentException('Height should be greater or equal to ' . self::MAX_DIMENSION_PX_HEIGHT);
        }
    }
}
