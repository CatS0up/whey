<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\Models\Media;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait HasThumbnail
{
    public function thumbnail(): MorphOne
    {
        return $this->morphOne(Media::class, 'mediable');
    }
}
