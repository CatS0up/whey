<?php

declare(strict_types=1);

namespace App\Models;

use App\Builders\EmailVerifyBuilder;
use App\DataObjects\Auth\EmailVerifyData;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\LaravelData\WithData;

class EmailVerify extends Model
{
    use HasFactory;
    use WithData;

    public const UPDATED_AT = null;

    protected static function booted(): void
    {
        static::saving(function (EmailVerify $model): void {
            $model->token = str()->random();
            $model->expire_at = now()->addSeconds(config('auth.email_verify.token_lifetime'));
        });
    }

    /** @var string */
    protected $dataClass = EmailVerifyData::class;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'expire_at' => 'datetime',
    ];

    /** {@inheritdoc} */
    public function newEloquentBuilder($query)
    {
        return new EmailVerifyBuilder($query);
    }

    public function isActive(): bool
    {
        return now()->lessThan($this->expire_at);
    }

    public function isExpired(): bool
    {
        return now()->greaterThanOrEqualTo($this->expire_at);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
