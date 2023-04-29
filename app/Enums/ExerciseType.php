<?php

declare(strict_types=1);

namespace App\Enums;

enum ExerciseType: string
{
    case Cardio = 'cardio';
    case Sprint = 'sprint';
    case Interval = 'interval';
    case Strength = 'strength';
    case Calisthenic = 'calisthenic';
    case Isometric = 'isometric';
    case DynamicStretching = 'dynamic_stretching';
    case StaticStretching = 'static_stretching';

    // TODO: Tłumaczenia
    public function label(): string
    {
        return match ($this) {
            self::Cardio => 'Kardio',
            self::Sprint => 'Sprint',
            self::Interval => 'Interwał',
            self::Strength => 'Siłowe',
            self::Calisthenic => 'Kalisteniczne',
            self::Isometric => 'Izometryczne',
            self::DynamicStretching => 'Rozciąganie dynamiczne',
            self::StaticStretching => 'Rozciąganie statyczne',
        };
    }

    public function availableColumns(): array
    {
        return match ($this) {
            self::Cardio => ['distance', 'time'],
            self::Sprint => ['distance', 'time'],
            self::Interval => ['time','reps', 'rest'],
            self::Strength => ['sets', 'reps', 'weight', 'tempo', 'rir/sir', 'rest'],
            self::Calisthenic => ['sets', 'reps', 'tempo', 'rir/sir', 'rest'],
            self::Isometric => ['sets', 'reps', 'time', 'rest'],
            self::DynamicStretching => ['sets', 'reps', 'rest'],
            self::StaticStretching => ['sets', 'reps', 'time', 'rest'],
        };
    }
}
