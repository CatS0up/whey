<?php

declare(strict_types=1);

namespace Tests\Abstracts;

use App\Models\EmailVerify;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\Authentication;
use Tests\TestCase;

abstract class EmailVerifyTestCase extends TestCase
{
    use Authentication;
    use RefreshDatabase;

    protected EmailVerify $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->token = $this->createToken();
    }

    protected function createToken(): EmailVerify
    {
        return EmailVerify::factory()
            ->active()
            ->create([
                'user_id' => $this->user->id,
            ]);
    }

    protected function makeTokenExpired(): void
    {
        $this->travel(config('auth.email_verify.token_lifetime'))->seconds();
    }
}
