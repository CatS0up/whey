<?php

declare(strict_types=1);

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use Propaganistas\LaravelPhone\PhoneNumber;

class Phone implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return phone(
            number: $attributes['phone'],
            country: $attributes['phone_country'],
        );
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if (!$value instanceof PhoneNumber) {
            throw new InvalidArgumentException('The given value is not an PhoneNumber instance');
        }

        $raw = (string) $value;
        return [
            'phone' => $raw,
            'phone_normalized' => preg_replace('[^0-9]', '', $raw),
            'phone_national' => preg_replace('[^0-9]', '', $value->formatNational()),
            'phone_e164' => $value->formatE164(),
            'phone_country' => $value->getCountry(),
        ];
    }
}
