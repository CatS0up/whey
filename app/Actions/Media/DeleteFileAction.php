<?php

declare(strict_types=1);

namespace App\Actions\Media;

use App\DataObjects\FileData;
use Illuminate\Filesystem\FilesystemManager as FileManager;

class DeleteFileAction
{
    public function __construct(
        private FileManager $manager,
    ) {
    }

    public function execute(FileData $data): bool
    {
        $this->manager->disk($data->disk)->delete($data->path);

        return $this->manager->disk($data->disk)->missing($data->path);
    }
}
