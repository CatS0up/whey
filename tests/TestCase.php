<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Storage;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected const TEST_DISK = 'test';

    protected function setUp(): void
    {
        parent::setUp();

       $this->prepareTestingDisk();
    }

    protected function prepareTestingDisk(): void
    {
        Storage::fake(self::TEST_DISK);

        config()->set('app.uploads.disk', self::TEST_DISK);
    }
}
