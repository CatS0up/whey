<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\Concerns\Media;
use Tests\Concerns\Authentication;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

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

        if (isset($uses[Authenctication::class])) {
            $this->setUpUser();
        }

        return $uses;
    }
}
