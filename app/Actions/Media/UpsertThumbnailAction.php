<?php

declare(strict_types=1);

namespace App\Actions\Media;

use App\Models\Contracts\ThumbnailInterface;
use App\Models\Media;
use App\Services\Media\UploadService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;

class UpsertThumbnailAction
{
    public function __construct(
        private UploadService $uploadService,
        private DeleteFileAction $deleteFileAction,
    ) {
    }

    public function execute(Model&ThumbnailInterface $target, UploadedFile $thumbnail): Media
    {
        if ($target->thumbnail->exists()) {
            $this->deleteFileAction->execute($target->thumbnail->getData());
        }

        $newThumbnailData = $this->uploadService->thumbnail($thumbnail);

        return $target->thumbnail->updateOrCreate(
            [
                'mediable_id' => $target->id,
                'mediable_type' => $target::class,
            ],
            Arr::except($newThumbnailData->all(), ['id']),
        );
    }
}
