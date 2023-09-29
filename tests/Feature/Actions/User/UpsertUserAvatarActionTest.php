<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\User;

use Tests\TestCase;
use App\Actions\Muscle\UpsertMuscleThumbnailAction;
use App\Actions\User\UpsertUserAvatarAction;
use App\Models\Media;
use App\Models\Muscle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Tests\Concerns\Authentication;
use Tests\Concerns\Media as HasMedia;


class UpsertUserAvatarActionTest extends TestCase
{
    use Authentication;
    use HasMedia;
    use RefreshDatabase;

    private UpsertUserAvatarAction $actionUnderTest;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actionUnderTest = app()->make(UpsertUserAvatarAction::class);
    }

    /** @test */
    public function it_should_create_and_resize_user_avatar_when_choosen_user_does_not_has_own_avatar(): void
    {
        $avatar = $this->createTestImage(name: 'avatar.png', width: 20, height: 20);

        $this->assertFalse($this->user->avatar()->exists());

        $upsertedAvatar = $this->actionUnderTest->execute($this->user->id, $avatar);
        $dimension = $this->getImageDimensionInfo($upsertedAvatar->getData());

        $this->assertTrue($this->user->avatar()->exists());
        $this->assertEquals(50, $dimension['width']);
        $this->assertEquals(50, $dimension['height']);
    }

    /** @test */
    public function it_should_create_and_resize_user_avatar_when_choosen_user_has_own_avatar(): void
    {
        $avatarData = $this->uploadService->avatar($this->createTestImage(), $this->user);

        $avatar = Media::query()->create(Arr::except($avatarData->all(), ['id']));
        $this->user->avatar()->save($avatar);

        $this->assertTrue($this->user->avatar->exists());

        $newAvatar = $this->createTestImage(name: 'avatar.png', width: 20, height: 20);
        $upsertedAvatar = $this->actionUnderTest->execute($this->user->id, $newAvatar);
        $newAvatarData = $upsertedAvatar->getData();
        $dimension = $this->getImageDimensionInfo($newAvatarData);

        $this->assertTrue($this->user->avatar->exists());
        $this->assertEquals(50, $dimension['width']);
        $this->assertEquals(50, $dimension['height']);

        $this->assertNotEquals($avatarData->name, $newAvatarData->name);
    }
}
