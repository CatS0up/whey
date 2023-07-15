<?php

declare(strict_types=1);

namespace App\Actions\Media;

use App\Models\Contracts\ThumbnailInterface;
use App\Models\Media;
use App\Services\Media\UploadService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;

class UpsertSmallThumbnailAction
{
    public function __construct(
        private UploadService $uploadService,
        private DeleteFileAction $deleteFileAction,
    ) {
    }

    public function execute(Model&ThumbnailInterface $target, UploadedFile $thumbnail): Media
    {
        if ($target->smallThumbnail->exists()) {
            $this->deleteFileAction->execute($target->smallThumbnail->getData());
        }

        $newSmallThumbnailData = $this->uploadService->smallThumbnail($thumbnail);

        return $target->smallThumbnail->updateOrCreate(
            [
                'mediable_id' => $target->id,
                'mediable_type' => $target::class,
            ],
            Arr::except($newSmallThumbnailData->all(), ['id']),
        );
    }
}
