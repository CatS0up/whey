<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\ValueObjects\Media\MediableInfo;
use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasMediableRelationship
{
    protected function mediableInfo(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value, array $attributes) => new MediableInfo(
                id: $attributes['id'],
                type: self::class,
            ),
        );
    }
}
