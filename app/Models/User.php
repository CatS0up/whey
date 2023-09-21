<?php

declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Casts\Height;
use App\Casts\Weight;
use App\Enums\PhoneCountry;
use App\Models\Concerns\HasSubdirectoryFilePath;
use App\Models\Contracts\Mediable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone' => RawPhoneNumberCast::class.':phone_country',
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


    public function exercises(): HasMany
    {
        return $this->hasMany(Exercise::class, 'author_id');
    }
}
