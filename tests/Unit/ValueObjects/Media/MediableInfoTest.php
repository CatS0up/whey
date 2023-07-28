<?php

declare(strict_types=1);

namespace Tests\Unit\ValueObjects\Media;

use App\ValueObjects\Media\MediableInfo;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class MediableInfoTest extends TestCase
{
    /** @test */
    public function it_should_throw_invalid_argument_exception_when_given_id_is_less_than_one(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('ID of mediable model cannot be less than 1');

        new MediableInfo(-1, 'Some\Dummy\Model\Class');
    }

    /** @test */
    public function it_should_throw_invalid_argument_exception_when_given_type_is_not_existing_class(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Some\Dummy\Model\Class model does not exists');

        new MediableInfo(1, 'Some\Dummy\Model\Class');
    }
}
