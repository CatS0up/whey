<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Exceptions\Shared\ModelHasNoProperty;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait Sluggable
{
    /** @var string */
    public const SLUG_FIELD = 'slug';
    /** @var int */
    public const INITIAL_SUFFIX = 0;

    abstract public function sluggableField(): string;

    public static function bootSluggable(): void
    {
        static::saving(function (Model $model): void {
            // @phpstan-ignore-next-line
            $model->slug = $model->generateUniqueSlug();
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

        if ($maxSlugSuffix) {
            return $this->addSuffiToRawSlug($rawSlug, $maxSlugSuffix);
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
                value: property_exists(self::class, 'id'),
                callback: fn (Builder $q) => $q->where('id', '!=', $this->id),
            )
            ->whereSlug($rawSlug)
            ->exists();
    }

    private function findModelWithMaxSuffix(string $rawSlug): ?self
    {
        // @phpstan-ignore-next-line
        return $this->query()
            ->selectRaw("SUBSTRING(slug, '[0-9]+$') as max_slug_suffix")
            ->where('slug', 'like', "{$rawSlug}%")
            // When the model is being created, the id property is not assigned, so we need to check it.
            ->when(
                value: property_exists(self::class, 'id'),
                callback: fn (Builder $q) => $q->where('id', '!=', $this->id),
            )
            ->orderByDesc('max_slug_suffix')
            ->first();
    }

    private function findMaxSlugSuffix(string $rawSlug): ?int
    {
        if ($this->rawSlugExists($rawSlug)) {
            return self::INITIAL_SUFFIX;
        }

        if ($modelWithMaxSuffix = $this->findModelWithMaxSuffix($rawSlug)) {
            return $modelWithMaxSuffix->max_slug_suffix;
        }

        return null;
    }

    private function addSuffiToRawSlug(string $rawSlug, int $maxSuffix): string
    {
        return str($rawSlug)
            ->finish('-'.($maxSuffix + 1))
            ->toString();
    }
}
