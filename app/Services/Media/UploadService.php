<?php

declare(strict_types=1);

namespace App\Services\Media;

use App\Actions\Media\UploadAvatarAction;
use App\Actions\Media\UploadCKEditorImageAction;
use App\Actions\Media\UploadSmallThumbnailAction;
use App\Actions\Media\UploadThumbnailAction;
use App\DataObjects\FileData;
use App\Models\Contracts\Mediable;
use Illuminate\Http\UploadedFile;

class UploadService
{
    public function thumbnail(UploadedFile $file, Mediable $model): FileData
    {
        return resolve(UploadThumbnailAction::class)->execute($file, $model);
    }

    public function smallThumbnail(UploadedFile $file, Mediable $model): FileData
    {
        return resolve(UploadSmallThumbnailAction::class)->execute($file, $model);
    }

    public function avatar(UploadedFile $file, Mediable $model): FileData
    {
        return resolve(UploadAvatarAction::class)->execute($file, $model);
    }

    public function ckeditorImage(UploadedFile $file): FileData
    {
        return resolve(UploadCKEditorImageAction::class)->execute($file);
    }
}
