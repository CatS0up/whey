<?php

declare(strict_types=1);

namespace App\Casts;

use App\ValueObjects\Shared\Weight as WeightValueObject;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class Weight implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return WeightValueObject::fromValueAndUnit(
            value: $attributes['weight'],
            unit: $attributes['weight_unit'],
        );
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if ( ! $value instanceof WeightValueObject) {
            throw new InvalidArgumentException('The given value is not an Weight instance');
        }

        return [
            'weight' => $value->value,
            'weight_unit' => $value->unit,
        ];
    }
}
