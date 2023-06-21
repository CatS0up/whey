<?php

declare(strict_types=1);

namespace App\Actions\Media;

use App\DataObjects\FileData;
use App\Exceptions\Media\FileNotFound;
use App\ValueObjects\Media\Dimension;
use Illuminate\Filesystem\FilesystemManager as FileManager;
use Intervention\Image\Image;
use Intervention\Image\ImageManager;

class ImageResizeAction
{
    /** @var bool */
    public const SHOULD_ASPECT_RATIO = true;

    /** @var bool */
    public const SHOULD_NOT_ASPECT_RATIO = false;

    public function __construct(
        private ImageManager $imageManager,
        private FileManager $fileManager,
    ) {
    }

    public function execute(FileData $fileData, Dimension $dimension, bool $shouldAspectRatio = self::SHOULD_NOT_ASPECT_RATIO): FileData
    {
        if ($this->fileManager->disk($fileData->disk)->missing($fileData->path)) {
            // TODO: Tłumaczenia
            throw FileNotFound::because('File not found ' . $fileData->path);
        }

        $fullPath = $this->fileManager->disk($fileData->disk)->path($fileData->path);
        $image = $this->imageManager->make($fullPath);

        if ($shouldAspectRatio) {
            $this->resizeWithAspectRation($image, $dimension);
        } else {
            $this->resize($image, $dimension);
        }

        return $fileData;
    }

    private function resize(Image $image, Dimension $dimension): Image
    {
        return $image
            ->resize(
                $dimension->width,
                $dimension->height,
            )
            ->save();
    }

    private function resizeWithAspectRation(Image $image, Dimension $dimension): Image
    {
        return $image
            ->resize(
                $dimension->width,
                $dimension->height,
                fn ($constraint) => $constraint->aspectRatio(),
            )
            ->save();
    }
}
