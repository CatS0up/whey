<?php

declare(strict_types=1);

namespace App\Actions\Media;

use App\Exceptions\Media\ModelWithoutThumbnailRelationship;
use App\Models\Media;
use App\Services\Media\UploadService;
use App\ValueObjects\Media\MediableInfo;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;

class UpsertThumbnailAction
{
    public function __construct(
        private UploadService $uploadService,
        private DeleteFileAction $deleteFileAction,
    ) {
    }

    public function execute(UploadedFile $file, MediableInfo $mediableInfo): Media
    {
        if ( ! method_exists($mediableInfo->type, 'thumbnail')) {
            // TODO: Tłumaczenia
            throw ModelWithoutThumbnailRelationship::because("{$mediableInfo->type} model does not have thumbnail relationship");
        }


        $target = $mediableInfo->type::query()->findOrFail($mediableInfo->id);

        if ($target->thumbnail()->exists()) {
            $this->deleteFileAction->execute($target->thumbnail->getData());
        }

        $newThumbnailData = $this->uploadService->thumbnail($file, $mediableInfo);

        return $target->thumbnail->updateOrCreate(
            [
                'mediable_id' => $mediableInfo->id,
                'mediable_type' => $mediableInfo->type,
                'collection' => 'thumbnails',
            ],
            Arr::except($newThumbnailData->all(), ['id']),
        );
    }
}
