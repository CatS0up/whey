<?php

declare(strict_types=1);

namespace App\Enums;

enum WeightUnit: string
{
    case Grams = 'g';
    case Ounces = 'oz';
    case Kilograms = 'kg';
    case Pounds = 'lbs';

    public function label(): string
    {
        return match ($this) {
            self::Grams => 'g',
            self::Kilograms => 'kg',
            self::Ounces => 'oz',
            self::Pounds => 'lbs',
        };
    }

    public function gramsValue(): float
    {
        return match ($this) {
            self::Grams => 1.0,
            self::Kilograms => 1000.0,
            self::Ounces => 28.3495231,
            self::Pounds => 453.59237,
        };
    }

    public function kilogramsValue(): float
    {
        return match ($this) {
            self::Grams => 0.001,
            self::Kilograms => 1.0,
            self::Ounces => 0.0283495231,
            self::Pounds => 0.45359237,
        };
    }

    public function ouncesValue(): float
    {
        return match ($this) {
            self::Grams => 0.0352739619,
            self::Kilograms => 35.2739619,
            self::Ounces => 1.0,
            self::Pounds => 16,
        };
    }

    public function poundsValue(): float
    {
        return match ($this) {
            self::Grams => 0.00220462262,
            self::Kilograms => 2.20462262,
            self::Ounces => 0.0625,
            self::Pounds => 1.0,
        };
    }

    public function otherUnitValue(WeightUnit $unit): float
    {
        return match ($unit) {
            $unit::Grams => $this->gramsValue(),
            $unit::Kilograms => $this->kilogramsValue(),
            $unit::Ounces => $this->ouncesValue(),
            $unit::Pounds => $this->poundsValue(),
        };
    }

    public function defaultConversion(): self
    {
        return match ($this) {
            self::Grams, self::Kilograms => self::Kilograms,
            self::Ounces, self::Pounds => self::Pounds,
        };
    }

    public function baseUnit(): self
    {
        return match ($this) {
            self::Grams, self::Kilograms => self::Grams,
            self::Ounces, self::Pounds => self::Ounces,
        };
    }
}
