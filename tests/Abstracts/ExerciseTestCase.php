<?php

declare(strict_types=1);

namespace Tests\Abstracts;

use App\Enums\ExerciseStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\Authentication;
use Tests\TestCase;

abstract class ExerciseTestCase extends TestCase
{
    use Authentication;
    use RefreshDatabase;

    public static function reviewableStatusesProvider(): array
    {
        return [
            'for_verfication status' => [
                ExerciseStatus::ForVerification,
            ],
            'rejected status' => [
                ExerciseStatus::Rejected,
            ],
        ];
    }

    public static function notReviewableStatusesProvider(): array
    {
        return [
            'verified status' => [
                ExerciseStatus::Verified,
            ],
            'private status' => [
                ExerciseStatus::Private,
            ],
        ];
    }
}
