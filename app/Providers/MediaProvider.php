<?php

declare(strict_types=1);

namespace App\Providers;

use App\Actions\Media\UploadAvatarAction;
use App\Actions\Media\UploadSmallThumbnailAction;
use App\Actions\Media\UploadThumbnailAction;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\ServiceProvider;

class MediaProvider extends ServiceProvider
{
    private array $actions = [
        UploadThumbnailAction::class,
        UploadSmallThumbnailAction::class,
        UploadAvatarAction::class,
    ];

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        foreach ($this->actions as $class) {
            $this->app->bind(
                abstract: $class,
                concrete: fn (Application $app) => new $class(
                    manager: $app->make(FilesystemManager::class),
                    disk: config('app.uploads.disk'),
                ),
            );
        }
    }
}
