<?php

declare(strict_types=1);

namespace App\Services\Media;

use App\Actions\Media\UploadSmallThumbnailAction;
use App\Actions\Media\UploadThumbnailAction;
use App\DataObjects\FileData;
use App\Models\Contracts\Mediable;
use Illuminate\Http\UploadedFile;

class UploadService
{
    public function __construct(
        private UploadThumbnailAction $uploadThumbnailAction,
        private UploadSmallThumbnailAction $uploadSmallThumbnailAction,
    ) {
    }

    public function thumbnail(UploadedFile $file, Mediable $model): FileData
    {
        return $this->uploadThumbnailAction->execute($file, $model);
    }

    public function smallThumbnail(UploadedFile $file, Mediable $model): FileData
    {
        return $this->uploadSmallThumbnailAction->execute($file, $model);
    }
}
