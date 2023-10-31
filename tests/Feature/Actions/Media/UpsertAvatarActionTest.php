<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Media;

use App\Actions\Media\UpsertAvatarAction;
use App\Models\Media;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Tests\Concerns\Media as HasMedia;
use Tests\TestCase;

class UpsertAvatarActionTest extends TestCase
{
    use HasMedia;
    use RefreshDatabase;

    private UpsertAvatarAction $actionUnderTest;

    protected function getMediableModelConfig(): array
    {
        return [
            'relationship' => 'avatar',
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->actionUnderTest = app()->make(UpsertAvatarAction::class);
    }

    /** @test */
    public function it_should_upsert_target_avatar_when_avatar_does_not_exists(): void
    {
        /** @var User $target */
        $target = $this->mediableModel;

        $this->assertFalse($target->avatar()->exists());

        $this->actionUnderTest->execute(
            file: $this->createTestImage(),
            model: $target,
        );

        $this->assertTrue($target->avatar->exists());
    }

    /** @test */
    public function it_should_upsert_target_avatar_when_avatar_exists(): void
    {
        /** @var User $target */
        $target = $this->mediableModel;
        $avatarData = $this->uploadService->avatar($this->createTestImage('avatar.png'), $target);
        $avatar = Media::query()->create($avatarData->allForUpsert());
        $target->avatar()->save($avatar);

        $this->assertTrue($target->avatar()->exists());

        $newAvatarFile = $this->createTestImage(
            name: 'avatar.png',
            width: 10,
            height: 10,
        );

        $newAvatarData = $this->actionUnderTest->execute(
            file: $newAvatarFile,
            model: $target,
        )
            ->getData();

        $this->assertNotEquals($avatarData->path, $newAvatarData->path);
    }
}
