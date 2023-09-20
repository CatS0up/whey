<?php

declare(strict_types=1);

namespace Tests\Concerns;

use App\DataObjects\FileData;
use App\Models\Contracts\Mediable;
use App\Models\Muscle;
use App\Services\Media\UploadService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait Media
{
    /** @var string */
    protected const TEST_DISK = 'testing_disk';
    /** @var string */
    protected const TEST_IMAGE_NAME = 'testing_image.jpg';
    /** @var int */
    protected const DEFAULT_TEST_IMAGE_WIDTH = 100;
    /** @var int */
    protected const DEFAULT_TEST_IMAGE_HEIGHT = 150;

    protected Mediable $mediableModel;
    protected UploadService $uploadService;

    protected function setUpMediableModel(): void
    {
        $this->afterApplicationCreated(function (): void {
            $this->mediableModel = $this->createMediableModel();
        });
    }

    protected function setUpUploadService(): void
    {
        $this->afterApplicationCreated(function (): void {
            $this->uploadService = app()->make(UploadService::class);
        });
    }

    protected function prepareTestDisk(): void
    {
        $this->afterApplicationCreated(function (): void {
            Storage::fake(self::TEST_DISK);

            // Set testing drive for all uploading files
            config()->set('app.uploads.disk', self::TEST_DISK);
        });
    }

    protected function createTestImage(
        $name = self::TEST_IMAGE_NAME,
        $width = self::DEFAULT_TEST_IMAGE_WIDTH,
        $height = self::DEFAULT_TEST_IMAGE_HEIGHT,
    ): UploadedFile {
        return UploadedFile::fake()->image(
            name: $name,
            width: $width,
            height: $height,
        );
    }

    protected function createHashFromPath(string $path): string
    {
        return hash_file(
            'sha256',
            Storage::disk(self::TEST_DISK)->path($path),
        );
    }

    protected function readFullImagePath(FileData $fileData): string
    {
        return Storage::disk($fileData->disk)->path($fileData->path);
    }

    protected function getImageDimensionInfo(FileData $fileData): array
    {
        $fullPath = $this->readFullImagePath($fileData);
        $imageInfo = getimagesize($fullPath);

        return [
            'width' => $imageInfo[0],
            'height' => $imageInfo[1],
        ];
    }

    protected function createMediableModel(): Mediable|Muscle
    {
        return Muscle::factory()->create();
    }
}
