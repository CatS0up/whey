<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Auth;

use App\Actions\Auth\RegisterAction;
use App\DataObjects\Auth\RegistrationData;
use App\Enums\HeightUnit;
use App\Enums\PhoneCountry;
use App\Enums\WeightUnit;
use App\Notifications\Auth\EmailVerificationNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\Concerns\Media;
use Tests\TestCase;

class RegisterActionTest extends TestCase
{
    use Media;
    use RefreshDatabase;

    private RegisterAction $actionUnderTest;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actionUnderTest = app()->make(RegisterAction::class);
    }

    /** @test */
    public function it_should_create_new_user_without_avatar(): void
    {
        $registerData = $this->createExampleRegistrationData();

        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('media', 0);

        $createdUser = $this->actionUnderTest->execute($registerData);

        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseCount('media', 0);
        $this->assertFalse($createdUser->avatar()->exists());
        $this->assertEquals($registerData->name, $createdUser->name);
        $this->assertEquals(str()->slug($registerData->name), $createdUser->slug);
        $this->assertEquals($registerData->email, $createdUser->email);

        // Value objects
        $this->assertTrue($registerData->phone->equals($createdUser->phone));
        $this->assertTrue($registerData->weight->equalsTo($createdUser->weight));
        $this->assertTrue($registerData->height->equalsTo($createdUser->height));
    }

    /** @test */
    public function it_should_create_new_user_with_avatar(): void
    {
        $registerData = $this->createExampleRegistrationData([
            'avatar' => $this->createTestImage('avatar.jpg'),
        ]);

        $this->assertDatabaseCount('users', 0);
        $this->assertDatabaseCount('media', 0);

        $createdUser = $this->actionUnderTest->execute($registerData);

        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseCount('media', 1);
        $this->assertTrue($createdUser->avatar()->exists());
    }

    /** @test */
    public function it_should_send_EmailVerificationNotification_after_registration(): void
    {
        Notification::fake();

        $registerData = $this->createExampleRegistrationData([
            'avatar' => $this->createTestImage('avatar.jpg'),
        ]);

        $createdUser = $this->actionUnderTest->execute($registerData);

        Notification::assertSentTo(
            notifiable: $createdUser,
            notification: EmailVerificationNotification::class,
            callback: fn (mixed $notification, array $channels): bool => in_array('mail', $channels),
        );
    }

    private function createExampleRegistrationData(array $extraData = []): RegistrationData
    {
        return RegistrationData::createFromArray([
            'name' => 'dummy_user',
            'password' => 'test',
            'email' => 'example@example.com',
            'phone' => '+48123456789',
            'phone_country' => PhoneCountry::Poland->value,
            'weight' => 70,
            'weight_unit' => WeightUnit::Kilograms->value,
            'height' => 1.75,
            'height_unit' => HeightUnit::Meters->value,
            ...$extraData,
        ]);
    }
}
