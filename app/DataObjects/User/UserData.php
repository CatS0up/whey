<?php

declare(strict_types=1);

namespace App\DataObjects\User;

use App\DataObjects\FileData;
use App\Models\User;
use App\ValueObjects\Shared\Height;
use App\ValueObjects\Shared\Weight;
use Spatie\LaravelData\Data;
use Propaganistas\LaravelPhone\PhoneNumber;
use Spatie\LaravelData\Contracts\DataObject;
use Spatie\LaravelData\Lazy;

final class UserData extends Data
{
    public function __construct(
        public readonly ?int $id = null,
        public readonly Lazy|FileData $avatar,
        public readonly string $name,
        public readonly string $slug,
        public readonly string $email,
        public readonly PhoneNumber $phone,
        public readonly float $bmi,
        public readonly Height $height,
        public readonly Weight $weight,
    ) {
    }

    public static function fromModel(User $user): self
    {
        return self::from([
            ...$user->toArray(),
            'avatar' => Lazy::whenLoaded(
                relation: 'avatar',
                model: $user,
                value: fn (): DataObject|FileData => $user->avatar->getData(),
            ),
        ]);
    }
}
