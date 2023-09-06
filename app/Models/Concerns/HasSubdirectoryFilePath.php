<?php

declare(strict_types=1);

namespace App\Models\Concerns;

trait HasSubdirectoryFilePath
{
    public function getSubDirectoryFilePath(): string
    {
        $modelName = str(class_basename($this->type()))->lower();

        return "{$modelName}/{$this->id()}";
    }
}
