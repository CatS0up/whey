<?php

declare(strict_types=1);

namespace App\Models\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphOne;

interface SmallThumbnailInterface
{
    public function smallThumbnail(): MorphOne;
}
