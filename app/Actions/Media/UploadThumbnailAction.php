<?php

declare(strict_types=1);

namespace App\Actions\Media;

use App\DataObjects\FileData;
use Illuminate\Filesystem\FilesystemManager as FileManager;
use Illuminate\Http\UploadedFile;
use Livewire\TemporaryUploadedFile;

class UploadThumbnailAction
{
    public function __construct(
        private FileManager $manager,
        private string $disk,
    ) {
    }

    public function execute(UploadedFile|TemporaryUploadedFile $file): FileData
    {
        $name = $file->hashName();

        $path = $this->manager->disk($this->disk)->put("thumbnails", $file);
        $fullPath = $this->manager->disk($this->disk)->path($path);

        return new FileData(
            id: null,
            name: $name,
            file_name: $file->getClientOriginalName(),
            mime_type: $file->getMimeType(),
            path: $path,
            disk: $this->disk,
            hash: hash_file(
                config('app.uploads.hash'),
                $fullPath,
            ),
            size: $file->getSize(),
            collection: 'thumbnails',
        );
    }
}
