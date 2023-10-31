<?php

declare(strict_types=1);

namespace App\Actions\Media;

use App\Models\Media;
use App\Services\Media\UploadService;
use Illuminate\Http\UploadedFile;

class CreateCKEditorImageAction
{
    public function __construct(
        private UploadService $uploadService,
        private Media $media,
    ) {
    }

    public function execute(UploadedFile $file): Media
    {
        $imageData = $this->uploadService->ckeditorImage($file);

        return $this->media->create($imageData->allForUpsert());
    }
}
