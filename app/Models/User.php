<?php

declare(strict_types=1);

namespace App\Models;

use App\Casts\Height;
use App\Casts\Phone;
use App\Casts\Weight;
use App\Enums\PhoneCountry;
use App\Models\Concerns\HasSubdirectoryFilePath;
use App\Models\Contracts\Mediable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Propaganistas\LaravelPhone\Casts\E164PhoneNumberCast;
use Propaganistas\LaravelPhone\Casts\RawPhoneNumberCast;

class User extends Authenticatable implements Mediable
{
    use HasApiTokens;
    use HasFactory;
    use HasSubdirectoryFilePath;
    use Notifiable;

    public const MIN_PASSWORD_LENGTH = 8;
    /** @var int */
    public const AVATAR_WIDTH = 50;
    /** @var int */
    public const AVATAR_HEIGHT = 50;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'weight',
        'height',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        // These fields are only for searching - start
        'phone_normalized',
        'phone_national',
        'phone_e164',
        // These fields are only for searching - end

        // These fields are available in the related value objects - start
        'phone_country',
        'weight_unit',
        'height_unit',
        // These fields are available in the related value objects - end
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone' => Phone::class,
        'phone_normalized' => RawPhoneNumberCast::class.':phone_country',
        'phone_national' => RawPhoneNumberCast::class.':phone_country',
        'phone_e164' => E164PhoneNumberCast::class.':phone_country',
        'phone_country' => PhoneCountry::class,
        'weight' => Weight::class,
        'height' => Height::class,
    ];

    public function id(): int
    {
        return $this->id;
    }

    public function type(): string
    {
        return self::class;
    }

    public function avatar(): MorphOne
    {
        return $this->morphOne(Media::class, 'mediable');
    }

    public function exercises(): HasMany
    {
        return $this->hasMany(Exercise::class, 'author_id');
    }
}
