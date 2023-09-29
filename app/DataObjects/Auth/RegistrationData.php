<?php

declare(strict_types=1);

namespace App\DataObjects\Auth;

use App\Enums\HeightUnit;
use App\Enums\WeightUnit;
use App\ValueObjects\Shared\Height;
use App\ValueObjects\Shared\Weight;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Spatie\LaravelData\Data;
use Propaganistas\LaravelPhone\PhoneNumber;

final class RegistrationData extends Data
{
    public function __construct(
        public readonly ?UploadedFile $avatar = null,
        public readonly string $name,
        public readonly string $email,
        public readonly PhoneNumber $phone,
        public readonly string $password,
        public readonly Weight $weight,
        public readonly Height $height,
    ) {
    }

    public static function createFromArray(array $data): self
    {
        $phone = phone(
            number:  data_get($data, 'phone'),
            country: data_get($data, 'phone_country'),
        );

        $weight = Weight::fromValueAndUnit(
            value: data_get($data, 'weight'),
            unit: WeightUnit::from(data_get($data, 'weight_unit')),
        );
        $height = Height::fromValueAndUnit(
            value: data_get($data, 'height'),
            unit: HeightUnit::from(data_get($data, 'height_unit')),
        );

        return self::from([
            'avatar' => data_get($data, 'avatar'),
            'name' => data_get($data, 'name'),
            'email' => data_get($data, 'email'),
            'password' => data_get($data, 'password'),
            'phone' => $phone,
            'weight' => $weight,
            'height' => $height,
        ]);
    }

    public function hasAvatarFile(): bool
    {
        return null !== $this->avatar;
    }

    public function allForCreation(): array
    {
        return Arr::except($this->all(), ['avatar']);
    }
}
