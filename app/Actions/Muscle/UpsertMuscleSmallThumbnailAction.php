<?php

declare(strict_types=1);

namespace App\Action\Muscle;

use App\Actions\Media\ImageResizeAction;
use App\Actions\Media\UpsertThumbnailAction;
use App\DataObjects\FileData;
use App\Models\Muscle;
use App\ValueObjects\Media\Dimension;
use Illuminate\Http\UploadedFile;

class UpsertMuscleSmallThumbnailAction
{
    public function __construct(
        private UpsertThumbnailAction $upsertThumbnailAction,
        private ImageResizeAction $imageResizeAction,
        private Muscle $muscle,
    ) {
    }

    public function execute(int $id, UploadedFile $thumbnail): Muscle
    {
        /** @var Muscle $target */
        $target = $this->muscle->query()->findOrFail($id);

        $thumbnail = $this->upsertThumbnailAction->execute($target, $thumbnail);

        /** @var FileData $fileData */
        $fileData = $thumbnail->getData();
        $this->imageResizeAction->execute(
            fileData: $fileData,
            dimension: new Dimension(Muscle::THUMBNAIL_WIDTH, Muscle::THUMBNAIL_HEIGHT),
            shouldAspectRatio: ImageResizeAction::SHOULD_ASPECT_RATIO,
        );

        return $target;
    }
}
