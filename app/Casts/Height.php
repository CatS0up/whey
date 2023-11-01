<?php

declare(strict_types=1);

namespace App\Casts;

use App\Enums\HeightUnit;
use App\ValueObjects\Shared\Height as HeightValueObject;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class Height implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return HeightValueObject::fromValueAndUnit(
            value: (float) $attributes['height'],
            unit: HeightUnit::tryFrom($attributes['height_unit']),
        );
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if ( ! $value instanceof HeightValueObject) {
            throw new InvalidArgumentException('The given value is not an Height instance');
        }

        return [
            'height' => $value->value,
            'height_unit' => $value->unit,
        ];
    }
}
