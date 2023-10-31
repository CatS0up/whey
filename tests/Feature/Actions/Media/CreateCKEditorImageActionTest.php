<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Media;

use App\Actions\Media\CreateCKEditorImageAction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Concerns\Media as HasMedia;
use Tests\TestCase;

class CreateCKEditorImageActionTest extends TestCase
{
    use HasMedia;
    use RefreshDatabase;

    private CreateCKEditorImageAction $actionUnderTest;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actionUnderTest = app()->make(CreateCKEditorImageAction::class);
    }

    /** @test */
    public function it_should_create_image_for_Ckeditor(): void
    {
        $image = $this->createTestImage('image.png');

        $actual = $this->actionUnderTest->execute($image);

        $this->assertDatabaseHas('media', [
            'id' => $actual->id,
            'name' => $actual->name,
            'path' => $actual->path,
            'disk' => $actual->disk,
        ]);
    }
}
