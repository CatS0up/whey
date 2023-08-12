<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\DataObjects\SelectInputData;

trait HasSelectInputData
{
    protected string $valueSelectColumn = 'id';
    protected string $labelSelectColumn = 'name';

    public function toSelectData(): SelectInputData
    {
        return new SelectInputData(
            value: $this->attributes[$this->valueSelectColumn],
            label: $this->attributes[$this->labelSelectColumn],
        );
    }
}
