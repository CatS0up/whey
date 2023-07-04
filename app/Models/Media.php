<?php

declare(strict_types=1);

namespace App\Models;

use App\DataObjects\FileData;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\LaravelData\WithData;

class Media extends Model
{
    use HasFactory;
    use WithData;

    /** @var string */
    protected $dataClass = FileData::class;

    /**
     * @var array<string>
     */
    protected $fillable = [
        'mediable_id',
        'mediable_type',
        'name',
        'file_name',
        'mime_type',
        'path',
        'disk',
        'file_hash',
        'collection',
        'size',
    ];

    public function mediable(): MorphTo
    {
        return $this->morphTo();
    }
}
