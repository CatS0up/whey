<?php

declare(strict_types=1);

namespace App\ValueObjects\Media;

use InvalidArgumentException;

final class Dimension
{
    public const MIN_DIMENSION_PX_WIDTH = 1;
    public const MIN_DIMENSION_PX_HEIGHT = 1;

    public function __construct(
        public readonly int $width,
        public readonly int $height,
    ) {
        if ($width < self::MIN_DIMENSION_PX_WIDTH) {
            throw new InvalidArgumentException('Width should be greater or equal to '.self::MIN_DIMENSION_PX_WIDTH);
        }

        if ($height < self::MIN_DIMENSION_PX_HEIGHT) {
            throw new InvalidArgumentException('Height should be greater or equal to '.self::MIN_DIMENSION_PX_HEIGHT);
        }
    }
}
