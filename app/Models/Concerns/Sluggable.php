<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Exceptions\Shared\ModelHasNoProperty;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

trait Sluggable
{
    /** @var string */
    public const SLUG_FIELD = 'slug';
    /** @var int */
    public const INITIAL_SUFFIX = 0;
    /** @var int */
    public const CHUNK_SIZE = 200;

    abstract private function sluggableField(): string;

    public static function bootSluggable(): void
    {
        static::saving(function (Model $model): void {
            // @phpstan-ignore-next-line
            if ($model->isDirty($model->sluggableField())) {
                // @phpstan-ignore-next-line
                $model->slug = $model->generateUniqueSlug();
            }
        });
    }

    private function generateRawSlug(string $string): string
    {
        return str($string)->slug()->toString();
    }

    private function generateUniqueSlug(): string
    {
        $this->ensureModelHasSlugField();
        $this->ensureModelHasSluggableField();

        $rawSlug = $this->generateRawSlug($this->getSluggableFieldValue());
        $maxSlugSuffix = $this->findMaxSlugSuffix($rawSlug);

        if (null !== $maxSlugSuffix) {
            return $this->addSuffixToRawSlug($rawSlug, $maxSlugSuffix);
        }

        return $rawSlug;
    }

    private function modelHasNoSluggableField(): bool
    {
        // @phpstan-ignore-next-line
        $sluggableFieldValueName = $this->sluggableField();

        // @phpstan-ignore-next-line
        return $this->modelHasNoProperty($sluggableFieldValueName);
    }

    private function modelHasNoSlugField(): bool
    {
        // @phpstan-ignore-next-line
        return $this->modelHasNoProperty(self::SLUG_FIELD);
    }

    private function ensureModelHasSlugField(): void
    {
        // @phpstan-ignore-next-line
        if ($this->modelHasNoSlugField()) {
            throw ModelHasNoProperty::because('Model has no \'slug\' property');
        }
    }

    private function ensureModelHasSluggableField(): void
    {
        // @phpstan-ignore-next-line
        if ($this->modelHasNoSluggableField($this->sluggableField())) {
            throw ModelHasNoProperty::because("Model has no {$this->sluggableField()} property");
        }
    }

    private function getSluggableFieldValue(): string
    {
        // @phpstan-ignore-next-line
        return $this->getAttribute($this->sluggableField());
    }

    private function modelHasNoProperty(string $property): bool
    {
        return ! $this->getConnection()
            ->getSchemaBuilder()
            ->hasColumn(
                table: $this->getTable(),
                column: $property,
            );
    }

    private function rawSlugExists(string $rawSlug): bool
    {
        return $this->query()
            // When the model is being created, the id property is not assigned, so we need to check it
            ->when(
                value: property_exists(self::class, $this->getKeyName()),
                callback: fn (Builder $q) => $q->where($this->getKeyName(), '!=', $this->getKey()),
            )
            ->whereSlug($rawSlug)
            ->exists();
    }

    private function findMaxSuffix(string $rawSlug): ?int
    {
        // @phpstan-ignore-next-line
        $maxSlugSuffix = 0;

        $this->query()
            ->where('slug', 'like', "{$rawSlug}%")
            ->where('slug', '<>', $rawSlug) // Slug without any suffix
            // When the model is being created, the id property is not assigned, so we need to check it.
            ->when(
                value: property_exists(self::class, $this->getKeyName()),
                callback: fn (Builder $q) => $q->where('id', '!=', $this->getKey())
            )
            ->orderBy($this->getKeyName()) // It's important to order by id for chunk method
            ->chunk(self::CHUNK_SIZE, function ($models) use (&$maxSlugSuffix): void {
                $maxSlugSuffix = $models->map(function (Model $model) {
                    // @phpstan-ignore-next-line
                    preg_match('/(\d+)$/', $model->slug, $matches);

                    return (int) Arr::first($matches);
                })->max();
            });

        return $maxSlugSuffix;
    }

    private function findMaxSlugSuffix(string $rawSlug): ?int
    {
        if ($maxSuffix = $this->findMaxSuffix($rawSlug)) {
            return $maxSuffix;
        }

        if ($this->rawSlugExists($rawSlug)) {
            return self::INITIAL_SUFFIX;
        }

        return null;
    }

    private function addSuffixToRawSlug(string $rawSlug, int $maxSuffix): string
    {
        return str($rawSlug)
            ->finish('-'.($maxSuffix + 1))
            ->toString();
    }
}
