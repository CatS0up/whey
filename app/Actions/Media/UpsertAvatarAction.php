<?php

declare(strict_types=1);

namespace App\Actions\Media;

use App\Exceptions\Media\ModelWithoutMediableRelationship;
use App\Models\Contracts\Mediable;
use App\Models\Media;
use App\Services\Media\UploadService;
use Illuminate\Http\UploadedFile;

class UpsertAvatarAction
{
    public function __construct(
        private UploadService $uploadService,
        private DeleteFileAction $deleteFileAction,
    ) {
    }

    public function execute(UploadedFile $file, Mediable $model): Media
    {
        if ( ! method_exists($model, 'avatar')) {
            throw ModelWithoutMediableRelationship::because("{$model->type()} model does not have avatar relationship");
        }

        if ($model->avatar()->exists()) {
            $this->deleteFileAction->execute($model->avatar->getData());
        }

        $newAvatarData = $this->uploadService->avatar($file, $model);

        return $model->avatar()->updateOrCreate(
            [
                'mediable_id' => $model->id(),
                'mediable_type' => $model->type(),
                'collection' => 'avatars',
            ],
            $newAvatarData->allForUpsert()
        );
    }
}
