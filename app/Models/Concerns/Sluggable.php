<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Exceptions\Shared\ModelHasNoProperty;
use Illuminate\Database\Eloquent\Model;

trait Sluggable
{
    /** @var string */
    public const SLUG_FIELD = 'slug';

    abstract public function sluggableField(): string;

    public static function bootSluggable(): void
    {
        static::saving(function (Model $model): void {
            // @phpstan-ignore-next-line
            $sluggableField = $model->getSluggableField($model);

            // @phpstan-ignore-next-line
            if ($model->modelHasNoSlugField($model)) {
                throw ModelHasNoProperty::because('Model has no \'slug\' property');
            }
            // @phpstan-ignore-next-line
            if ($model->modelHasNoSluggableField($model)) {
                throw ModelHasNoProperty::because("Model has no {$sluggableField} property");
            }

            // @phpstan-ignore-next-line
            $model->slug = $model->generateSlug($sluggableField);
        });
    }

    public function generateSlug(string $string): string
    {
        return str()->slug($string);
    }

    private function getSluggableField(Model $model): string
    {
        // @phpstan-ignore-next-line
        return $model->getAttribute($model->sluggableField());
    }

    private function modelHasNoSluggableField(Model $model): bool
    {
        // @phpstan-ignore-next-line
        $sluggableFieldName = $model->sluggableField();

        // @phpstan-ignore-next-line
        return $model->modelHasNoProperty($model, $sluggableFieldName);
    }

    private function modelHasNoSlugField(Model $model): bool
    {
        // @phpstan-ignore-next-line
        return $model->modelHasNoProperty($model, self::SLUG_FIELD);
    }

    private function modelHasNoProperty(Model $model, string $property): bool
    {
        return ! $model->getConnection()
            ->getSchemaBuilder()
            ->hasColumn(
                table: $model->getTable(),
                column: $property,
            );
    }
}
