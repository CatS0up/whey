<?php

declare(strict_types=1);

namespace App\Enums;

enum HeightUnit: string
{
    case Centimeters = 'cm';
    case Meters = 'm';
    case Inches = 'in';
    case Foots = 'ft';

    public function label(): string
    {
        return match ($this) {
            self::Centimeters => 'cm',
            self::Meters => 'm',
            self::Inches => 'in',
            self::Foots => 'ft',
        };
    }

    public function centimetersValue(): float
    {
        return match ($this) {
            self::Centimeters => 1.0,
            self::Meters => 100.0,
            self::Inches => 2.54,
            self::Foots => 30.48,
        };
    }

    public function metersValue(): float
    {
        return match ($this) {
            self::Centimeters => 0.01,
            self::Meters => 1.0,
            self::Inches => 0.0254,
            self::Foots => 0.3048,
        };
    }

    public function inchesValue(): float
    {
        return match ($this) {
            self::Centimeters => 0.393700787,
            self::Meters => 39.3700787,
            self::Inches => 1.0,
            self::Foots => 12.0,
        };
    }

    public function footsValue(): float
    {
        return match ($this) {
            self::Centimeters => 0.032808399,
            self::Meters => 3.2808399,
            self::Inches => 0.0833333333,
            self::Foots => 1.0,
        };
    }

    public function otherUnitValue(HeightUnit $unit): float
    {
        return match ($unit) {
            $unit::Centimeters => $this->centimetersValue(),
            $unit::Meters => $this->metersValue(),
            $unit::Inches => $this->inchesValue(),
            $unit::Foots => $this->footsValue(),
        };
    }

    public function defaultConversion(): self
    {
        return match ($this) {
            self::Centimeters, self::Meters => self::Meters,
            self::Inches, self::Foots => self::Foots,
        };
    }

    public function baseUnit(): self
    {
        return match ($this) {
            self::Centimeters, self::Meters => self::Centimeters,
            self::Inches, self::Foots => self::Inches,
        };
    }
}
