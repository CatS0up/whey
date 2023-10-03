<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;
use Tests\Concerns\Media;
use Tests\Concerns\Authentication;
use Tests\Concerns\SkipReCaptchaValidation;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    /**
     * Boot the testing helper traits.
     *
     * @return array
     */
    protected function setUpTraits(): array
    {
        $uses = parent::setUpTraits();

        if (isset($uses[Media::class])) {
            $this->prepareTestDisk();
            $this->setUpMediableModel();
            $this->setUpUploadService();
        }

        if (isset($uses[Authentication::class])) {
            $this->setUpUser();
        }

        if (isset($uses[SkipReCaptchaValidation::class])) {
            $this->skipReCaptchaValidation();
        }

        return $uses;
    }

    protected function clearTable(string $table): void
    {
        DB::table($table)->truncate();
    }
}
