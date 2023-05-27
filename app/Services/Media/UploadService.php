<?php

declare(strict_types=1);

namespace App\Services\Media;

use App\Actions\Media\UploadThumbnailAction;
use App\DataObjects\FileData;
use Illuminate\Http\UploadedFile;
use Livewire\TemporaryUploadedFile;

class UploadService
{
    public function __construct(
        private UploadThumbnailAction $uploadThumbnailAction,
    ) {
    }

    public function thumbnail(UploadedFile|TemporaryUploadedFile $file): FileData
    {
        return $this->uploadThumbnailAction->execute($file);
    }
}
