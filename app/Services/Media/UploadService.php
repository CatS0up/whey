<?php

declare(strict_types=1);

namespace App\Services\Media;

use App\Actions\Media\UploadSmallThumbnailAction;
use App\Actions\Media\UploadThumbnailAction;
use App\DataObjects\FileData;
use App\ValueObjects\Media\MediableInfo;
use Illuminate\Http\UploadedFile;

class UploadService
{
    public function __construct(
        private UploadThumbnailAction $uploadThumbnailAction,
        private UploadSmallThumbnailAction $uploadSmallThumbnailAction,
    ) {
    }

    public function thumbnail(UploadedFile $file, MediableInfo $mediableInfo): FileData
    {
        return $this->uploadThumbnailAction->execute($file, $mediableInfo);
    }

    public function smallThumbnail(UploadedFile $file, MediableInfo $mediableInfo): FileData
    {
        return $this->uploadSmallThumbnailAction->execute($file, $mediableInfo);
    }
}
