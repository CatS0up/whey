<?php

declare(strict_types=1);

namespace App\Models;

use App\Builders\UserBuilder;
use App\Casts\Height;
use App\Casts\Phone;
use App\Casts\Weight;
use App\DataObjects\User\UserData;
use App\Enums\HeightUnit;
use App\Enums\PhoneCountry;
use App\Enums\WeightUnit;
use App\Models\Concerns\HasSubdirectoryFilePath;
use App\Models\Concerns\Sluggable;
use App\Models\Contracts\Mediable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Propaganistas\LaravelPhone\Casts\E164PhoneNumberCast;
use Propaganistas\LaravelPhone\Casts\RawPhoneNumberCast;
use Spatie\LaravelData\WithData;

/**
 * @method static UserBuilder<User> query()
 */
class User extends Authenticatable implements Mediable
{
    use HasApiTokens;
    use HasFactory;
    use HasSubdirectoryFilePath;
    use Notifiable;
    use Sluggable;
    use WithData;

    public const MIN_PASSWORD_LENGTH = 8;
    /** @var int */
    public const AVATAR_WIDTH = 50;
    /** @var int */
    public const AVATAR_HEIGHT = 50;
    /** @var bool */
    public const HAS_TEMPORARY_PASSWORD = true;

    protected $attributes = [
        'has_temporary_password' => false,
    ];

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
        'weight_unit' => WeightUnit::class,
        'height_unit' => HeightUnit::class,
        'has_temporary_password' => 'boolean',
    ];

    /** @var string */
    protected $dataClass = UserData::class;

    /**
     * @param \Illuminate\Database\Query\Builder $query
     *
     * @return UserBuilder<User>
     */
    public function newEloquentBuilder($query): UserBuilder
    {
        return new UserBuilder($query);
    }

    public function sluggableField(): string
    {
        return 'name';
    }

    public function id(): int
    {
        return $this->id;
    }

    public function type(): string
    {
        return self::class;
    }

    /** Accessors/Mutators - start */
    protected function password(): Attribute
    {
        return new Attribute(
            set: fn (string $value): string => Hash::make($value),
        );
    }
    /** Accessors/Mutators - end */

    /** Relationships - start */
    public function emailVerifyToken(): HasOne
    {
        return $this->hasOne(EmailVerify::class)->latest();
    }

    public function avatar(): MorphOne
    {
        return $this->morphOne(Media::class, 'mediable');
    }

    public function verifiedExercises(): HasMany
    {
        return $this->hasMany(Exercise::class);
    }

    public function exercises(): HasMany
    {
        return $this->hasMany(Exercise::class, 'author_id');
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }
    /** Relationships - end */
}
