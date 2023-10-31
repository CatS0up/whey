<?php

declare(strict_types=1);

namespace App\Models;

use App\DataObjects\FileData;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\LaravelData\WithData;

class Media extends Model
{
    use HasFactory;
    use WithData;

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

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['full_path', 'url'];

    /** @var string */
    protected $dataClass = FileData::class;

    /** Accessors/Mutators - start */
    protected function fullPath(): Attribute
    {
        return new Attribute(
            get: fn (): string => file_full_path($this->disk, $this->path),
        );
    }

    protected function url(): Attribute
    {
        return new Attribute(
            get: fn (): string => file_url($this->disk, $this->path),
        );
    }
    /** Accessors/Mutators - end */

    public function mediable(): MorphTo
    {
        return $this->morphTo();
    }
}
