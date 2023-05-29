<?php

declare(strict_types=1);

namespace Tests\Unit\ValueObjects\Media;

use App\ValueObjects\Media\Dimension as MediaDimension;
use PHPUnit\Framework\TestCase;

class DimensionTest extends TestCase
{
    /**
     * @test
     */
    public function it_should_throw_invalid_argument_exception_when_given_width_is_less_than_one(): void
    {
       $this->expectException(\InvalidArgumentException::class);
       $this->expectExceptionMessage('Width should be greater or equal to 1');

       new MediaDimension(-1, 100);
    }

    /**
     * @test
     */
    public function it_should_throw_invalid_argument_exception_when_given_height_is_less_than_one(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Height should be greater or equal to 1');

        new MediaDimension(100, -1);
    }
}
