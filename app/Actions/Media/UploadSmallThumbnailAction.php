<?php

declare(strict_types=1);

namespace App\Actions\Media;

use App\DataObjects\FileData;
use App\ValueObjects\Media\MediableInfo;
use Illuminate\Filesystem\FilesystemManager as FileManager;
use Illuminate\Http\UploadedFile;

class UploadSmallThumbnailAction
{
    public function __construct(
        private FileManager $manager,
        private string $disk,
    ) {
    }

    public function execute(UploadedFile $file, MediableInfo $mediableInfo): FileData
    {
        $name = $file->hashName();

        $path = $file->store("small_thumbnails/{$mediableInfo->getSubDirectoryFilePath()}", $this->disk);
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
            collection: 'small_thumbnails',
        );
    }
}
