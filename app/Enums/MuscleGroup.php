<?php

declare(strict_types=1);

namespace App\Enums;

enum MuscleGroup: string
{
    case Deltoid = 'deltoid';
    case Chest = 'chest';
    case Biceps = 'biceps';
    case Brachialis = 'brachialis';
    case Triceps = 'triceps';
    case Forearm = 'forearm';
    case Back = 'back';
    case Abdominal = 'abdominal';
    case Glutes = 'glutes';
    case Drumsticks = 'drumsticks';
    case Legs = 'legs';

    // TODO: Tłumaczenia
    public function label(): string
    {
        return match ($this) {
            self::Deltoid => 'Mięśnie naramienne',
            self::Chest => 'Klatka piersiowa',
            self::Biceps => 'Biceps',
            self::Brachialis => 'Mięsienie ramienne',
            self::Triceps => 'Triceps',
            self::Forearm => 'Przedramie',
            self::Back => 'Plecy',
            self::Abdominal => 'Mięśnie brzucha',
            self::Glutes => 'Posladki',
            self::Legs => 'Nogi',
            self::Drumsticks => 'Podudzia',
        };
    }
}
