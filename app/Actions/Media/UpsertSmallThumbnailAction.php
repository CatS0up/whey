<?php

declare(strict_types=1);

namespace App\Actions\Media;

use App\Exceptions\Media\ModelWithoutSmallThumbnailRelationship;
use App\Models\Media;
use App\Services\Media\UploadService;
use App\ValueObjects\Media\MediableInfo;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;

class UpsertSmallThumbnailAction
{
    public function __construct(
        private UploadService $uploadService,
        private DeleteFileAction $deleteFileAction,
    ) {
    }

    public function execute(UploadedFile $file, MediableInfo $mediableInfo): Media
    {
        if ( ! method_exists($mediableInfo->type, 'smallThumbnail')) {
            // TODO: Tłumaczenia
            throw ModelWithoutSmallThumbnailRelationship::because("{$mediableInfo->type} model does not have smallThumbnail relationship");
        }

        $target = $mediableInfo->type::query()->findOrFail($mediableInfo->id);

        if ($target->smallThumbnail()->exists()) {
            $this->deleteFileAction->execute($target->smallThumbnail->getData());
        }

        $newSmallThumbnailData = $this->uploadService->smallThumbnail($file, $mediableInfo);

        return $target->smallThumbnail->updateOrCreate(
            [
                'mediable_id' => $mediableInfo->id,
                'mediable_type' => $mediableInfo->type,
                'collection' => 'small_thumbnails',
            ],
            Arr::except($newSmallThumbnailData->all(), ['id']),
        );
    }
}
