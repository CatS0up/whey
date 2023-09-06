<?php

declare(strict_types=1);

namespace App\Models\Contracts;

use App\ValueObjects\Form\SelectOption;

interface Selectable
{
    public function selectOptionValue(): string;
    public function selectOptionLabel(): string;
    public function toSelectOption(): SelectOption;
}
