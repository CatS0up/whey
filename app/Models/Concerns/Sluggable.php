<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Model;

trait Sluggable
{
    abstract public function sluggableField(): string;

    public static function bootSluggable()
    {
        static::saving(function (Model $model): void {
            $sluggableField = $model->getAttribute($model->sluggableField());
            $model->slug = $model->generateSlug($sluggableField);
        });
    }

    public function generateSlug(string $string): string
    {
        return str()->slug($string);
    }
}
