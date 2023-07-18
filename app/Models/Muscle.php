<?php

declare(strict_types=1);

namespace App\Models;

use App\DataObjects\MuscleData;
use App\Models\Concerns\HasSmallThumbnail;
use App\Models\Concerns\HasThumbnail;
use App\Models\Contracts\SmallThumbnailInterface;
use App\Models\Contracts\ThumbnailInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\WithData;

class Muscle extends Model implements ThumbnailInterface, SmallThumbnailInterface
{
    use HasFactory;
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

    /** @var string */
    protected $dataClass = MuscleData::class;

    /** @var bool */
    public $timestamps = false;
}
