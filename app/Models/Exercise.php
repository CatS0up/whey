<?php

declare(strict_types=1);

namespace App\Models;

use App\DataObjects\ExerciseData;
use App\Enums\DifficultyLevel;
use App\Enums\ExerciseType;
use App\Models\Concerns\HasSmallThumbnail;
use App\Models\Concerns\HasThumbnail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\LaravelData\WithData;

class Exercise extends Model
{
    use HasFactory;
    use HasSmallThumbnail;
    use HasThumbnail;
    use WithData;

    /** @var bool */
    public const IS_PUBLIC = true;
    /** @var int */
    public const THUMBNAIL_WIDTH = 200;
    /** @var int */
    public const THUMBNAIL_HEIGHT = 200;
    /** @var int */
    public const SMALL_THUMBNAIL_WIDTH = 50;
    /** @var int */
    public const SMALL_THUMBNAIL_HEIGHT = 50;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'difficulty_level',
        'type',
        'instructions_html',
        'is_public',
        'author_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'difficulty_level' => DifficultyLevel::class,
        'type' => ExerciseType::class,
        'is_public' => 'boolean',
    ];

    /** @var string */
    protected $dataClass = ExerciseData::class;

    public function author(): HasOne
    {
        return $this->hasOne(User::class, 'author_id');
    }

    public function muscles(): BelongsToMany
    {
        return $this->belongsToMany(Muscle::class);
    }
}
