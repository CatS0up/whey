<?php

declare(strict_types=1);

namespace App\Actions\Muscle;

use App\Actions\Media\ImageResizeAction;
use App\Actions\Media\UpsertSmallThumbnailAction;
use App\DataObjects\FileData;
use App\Models\Media;
use App\Models\Muscle;
use App\ValueObjects\Media\Dimension;
use Illuminate\Http\UploadedFile;

class UpsertMuscleSmallThumbnailAction
{
    public function __construct(
        private UpsertSmallThumbnailAction $upsertThumbnailAction,
        private ImageResizeAction $imageResizeAction,
        private Muscle $muscle,
    ) {
    }

    public function execute(int $id, UploadedFile $file): Media
    {
        /** @var Muscle $target */
        $target = $this->muscle->query()->findOrFail($id);

        $smallThumbnail = $this->upsertThumbnailAction->execute($target, $file);

        /** @var FileData $fileData */
        $fileData = $smallThumbnail->getData();
        $this->imageResizeAction->execute(
            fileData: $fileData,
            dimension: new Dimension(Muscle::THUMBNAIL_WIDTH, Muscle::THUMBNAIL_HEIGHT),
            shouldAspectRatio: ImageResizeAction::SHOULD_ASPECT_RATIO,
        );

        return $smallThumbnail;
    }
}
