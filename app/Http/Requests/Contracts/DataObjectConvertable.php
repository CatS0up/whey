<?php

declare(strict_types=1);

namespace App\Http\Requests\Contracts;

use Spatie\LaravelData\Contracts\DataObject;

interface DataObjectConvertable
{
    public function toDataObject(): DataObject;
}
