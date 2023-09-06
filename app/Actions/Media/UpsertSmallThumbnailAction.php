<?php

declare(strict_types=1);

namespace App\Actions\Media;

use App\Exceptions\Media\ModelWithoutSmallThumbnailRelationship;
use App\Models\Contracts\Mediable;
use App\Models\Media;
use App\Services\Media\UploadService;
use Illuminate\Http\UploadedFile;

class UpsertSmallThumbnailAction
{
    public function __construct(
        private UploadService $uploadService,
        private DeleteFileAction $deleteFileAction,
    ) {
    }

    public function execute(UploadedFile $file, Mediable $model): Media
    {
        if ( ! method_exists($model, 'smallThumbnail')) {
            throw ModelWithoutSmallThumbnailRelationship::because("{$model->type()} model does not have smallThumbnail relationship");
        }

        if ($model->smallThumbnail()->exists()) {
            $this->deleteFileAction->execute($model->smallThumbnail->getData());
        }

        $newSmallThumbnailData = $this->uploadService->smallThumbnail($file, $model);

        return $model->smallThumbnail->updateOrCreate(
            [
                'mediable_id' => $model->id(),
                'mediable_type' => $model->type(),
                'collection' => 'small_thumbnails',
            ],
            $newSmallThumbnailData->allForUpsert()
        );
    }
}
