<?php

declare(strict_types=1);

namespace App\Actions\Media;

use App\DataObjects\FileData;
use App\Models\Contracts\Mediable;
use Illuminate\Filesystem\FilesystemManager as FileManager;
use Illuminate\Http\UploadedFile;

class UploadAvatarAction
{
    public function __construct(
        private FileManager $manager,
        private string $disk,
    ) {
    }

    public function execute(UploadedFile $file, Mediable $model): FileData
    {
        $name = $file->hashName();

        $path = $file->store("avatars/{$model->getSubDirectoryFilePath()}", $this->disk);
        $fullPath = $this->manager->disk($this->disk)->path($path);

        return new FileData(
            id: null,
            name: $name,
            file_name: $file->getClientOriginalName(),
            mime_type: $file->getMimeType(),
            path: $path,
            disk: $this->disk,
            file_hash: hash_file(
                config('app.uploads.hash'),
                $fullPath,
            ),
            size: $file->getSize(),
            collection: 'avatars',
        );
    }
}
