<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Media;

use App\Actions\Media\ImageResizeAction;
use App\DataObjects\FileData;
use App\Exceptions\Media\FileNotFound;
use App\ValueObjects\Media\Dimension;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\Concerns\Media;
use Tests\TestCase;

class ImageResizeActionTest extends TestCase
{
    use Media;
    use RefreshDatabase;

    private ImageResizeAction $actionUnderTest;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actionUnderTest = app()->make(ImageResizeAction::class);
    }

    /**
     * @test
     * */
    public function it_should_throw_FileNotFoundException_when_given_file_does_not_exists(): void
    {
        $this->expectException(FileNotFound::class);
        $this->expectExceptionMessage('File not found none/existing/path/none_existing_file.png');

        $imageData = new FileData(
            id: null,
            name: 'none_existing_file',
            file_name: 'none_existing_file',
            mime_type: 'image/png',
            path: 'none/existing/path/none_existing_file.png',
            disk: self::TEST_DISK,
            file_hash: 'dummy_hash',
            collection: 'dummy_collection',
            size: 64,
        );

        $this->actionUnderTest->execute($imageData, new Dimension(10, 10));
    }

    /**
     * @test
     */
    public function it_should_resize_given_image(): void
    {
        $imageFileData = $this->createTestingFileWithModel();

        $dimension = $this->getImageDimensionInfo($imageFileData);
        $this->assertEquals(100, $dimension['width']);
        $this->assertEquals(150, $dimension['height']);

        $this->actionUnderTest->execute(
            fileData: $imageFileData,
            dimension: new Dimension(
                width: 200,
                height: 300,
            ),
        );

        $dimension = $this->getImageDimensionInfo($imageFileData);
        $this->assertEquals(200, $dimension['width']);
        $this->assertEquals(300, $dimension['height']);
    }

    /** @test */
    public function it_should_resize_given_image_with_aspect_ratio(): void
    {
        $imageFileData = $this->createTestingFileWithModel();

        $dimension = $this->getImageDimensionInfo($imageFileData);
        $this->assertEquals(100, $dimension['width']);
        $this->assertEquals(150, $dimension['height']);

        $this->actionUnderTest->execute(
            fileData: $imageFileData,
            dimension: new Dimension(
                width: 100,
                height: 100,
            ),
            shouldAspectRatio: ImageResizeAction::SHOULD_ASPECT_RATIO,
        );

        $dimension = $this->getImageDimensionInfo($imageFileData);
        $this->assertEquals(67, $dimension['width']);
        $this->assertEquals(100, $dimension['height']);
    }

    /**
     * @test
     */
    public function it_should_overwrite_previous_image_after_resize(): void
    {
        $imageFileData = $this->createTestingFileWithModel();

        $this->assertCount(1, Storage::disk(self::TEST_DISK)->allFiles());

        $this->actionUnderTest->execute(
            fileData: $imageFileData,
            dimension: new Dimension(
                width: 100,
                height: 100,
            ),
        );

        $this->assertCount(1, Storage::disk(self::TEST_DISK)->allFiles());
    }

    /** @test */
    public function it_should_not_change_image_path_after_resize(): void
    {
        $image = $this->createTestingFileWithModel();
        $fullPath = Storage::disk($image->disk)->path($image->path);

        $resized = $this->actionUnderTest->execute($image, new Dimension(10, 10));

        $this->assertEquals($fullPath, $this->readFullImagePath($resized));
    }

    private function createTestingFileWithModel(
        int $width = self::DEFAULT_TEST_IMAGE_WIDTH,
        int $height = self::DEFAULT_TEST_IMAGE_HEIGHT,
        string $name = self::TEST_IMAGE_NAME,
    ): FileData {
        return $this->uploadService->thumbnail(
            $this->createTestImage(
                width: $width,
                height: $height,
                name: $name,
            ),
            $this->mediableModel,
        );
    }
}
