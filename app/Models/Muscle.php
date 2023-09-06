<?php

declare(strict_types=1);

namespace App\Models;

use App\DataObjects\MuscleData;
use App\Enums\MuscleGroup;
use App\Models\Concerns\HasSelectOption;
use App\Models\Concerns\HasSmallThumbnail;
use App\Models\Concerns\HasSubdirectoryFilePath;
use App\Models\Concerns\HasThumbnail;
use App\Models\Contracts\Mediable;
use App\Models\Contracts\Selectable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\WithData;

class Muscle extends Model implements Selectable, Mediable
{
    use HasFactory;
    use HasSelectOption;
    use HasSmallThumbnail;
    use HasSubdirectoryFilePath;
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

    public function selectOptionValue(): string
    {
        return (string) $this->id;
    }

    public function selectOptionLabel(): string
    {
        return $this->name;
    }

    public function id(): int
    {
        return $this->id;
    }

    public function type(): string
    {
        return self::class;
    }
}
