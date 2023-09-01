<?php

declare(strict_types=1);

namespace App\Models;

use App\DataObjects\MuscleData;
use App\Enums\MuscleGroup;
use App\Models\Concerns\HasMediableRelationship;
use App\Models\Concerns\HasSelectInputData;
use App\Models\Concerns\HasSmallThumbnail;
use App\Models\Concerns\HasThumbnail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\LaravelData\WithData;

class Muscle extends Model
{
    use HasFactory;
    use HasMediableRelationship;
    use HasSelectInputData;
    use HasSmallThumbnail;
    use HasThumbnail;
    use WithData;

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
        'muscle_group',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'muscle_group' => MuscleGroup::class,
    ];

    /** @var string */
    protected $dataClass = MuscleData::class;

    /** @var bool */
    public $timestamps = false;
}
