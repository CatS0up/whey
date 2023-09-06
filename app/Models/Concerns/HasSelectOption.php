<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use App\ValueObjects\Form\SelectOption;

trait HasSelectOption
{
    public function toSelectOption(): SelectOption
    {
        return new SelectOption(
            value: $this->selectOptionValue(),
            label: $this->selectOptionLabel(),
        );
    }
}
