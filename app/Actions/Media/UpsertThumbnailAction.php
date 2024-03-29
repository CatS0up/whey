<?php

declare(strict_types=1);

namespace App\Actions\Media;

use App\Exceptions\Media\ModelWithoutMediableRelationship;
use App\Models\Contracts\Mediable;
use App\Models\Media;
use App\Services\Media\UploadService;
use Illuminate\Http\UploadedFile;

class UpsertThumbnailAction
{
    public function __construct(
        private UploadService $uploadService,
        private DeleteFileAction $deleteFileAction,
    ) {
    }

    public function execute(UploadedFile $file, Mediable $model): Media
    {
        if ( ! method_exists($model, 'thumbnail')) {
            // TODO: Tłumaczenia
            throw ModelWithoutMediableRelationship::because("{$model->type()} model does not have thumbnail relationship");
        }

        if ($model->thumbnail()->exists()) {
            $this->deleteFileAction->execute($model->thumbnail->getData());
        }

        $newThumbnailData = $this->uploadService->thumbnail($file, $model);

        return $model->thumbnail()->updateOrCreate(
            [
                'mediable_id' => $model->id(),
                'mediable_type' => $model->type(),
                'collection' => 'thumbnails',
            ],
            $newThumbnailData->allForUpsert(),
        );
    }
}
