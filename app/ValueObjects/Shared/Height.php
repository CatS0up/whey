<?php

declare(strict_types=1);

namespace App\ValueObjects\Shared;

use App\Enums\HeightUnit;
use InvalidArgumentException;
use Stringable;

final readonly class Height implements Stringable
{
    private const MIN_VALUE = 0;

    private function __construct(
        public float $value,
        public HeightUnit $unit,
    ) {
        if (self::MIN_VALUE > $value) {
            throw new InvalidArgumentException('Value should be greater or equals to '.self::MIN_VALUE);
        }
    }

    /** {@inheritdoc} */
    public function __toString()
    {
        $defaultConversion = $this->unit->defaultConversion();
        $defaultConversionBaseUnit = $defaultConversion->baseUnit();
        $valueInDefaultConversionUnit = $this->value * $this->unit->otherUnitValue($defaultConversion);

        $base = intval($valueInDefaultConversionUnit);
        $rest = ($valueInDefaultConversionUnit - $base) * $defaultConversion->otherUnitValue($defaultConversionBaseUnit);
        $rest = round($rest, 3);

        return "{$base}{$defaultConversion->label()} {$rest}{$defaultConversionBaseUnit->label()}";
    }

    public static function fromValueAndUnit(float $value, HeightUnit $unit): self
    {
        return new self($value, $unit);
    }

    public function toOtherUnit(HeightUnit $unit): self
    {
        $value = $this->value * $this->unit->otherUnitValue($unit);

        return new self($value, $unit);
    }

    public function equalsTo(Height $compareTo): bool
    {
        $inCurrentUnit = $compareTo->toOtherUnit($this->unit);

        return compare_float($this->value, $inCurrentUnit->value);
    }

    public function notEqualsTo(Height $compareTo): bool
    {
        $inCurrentUnit = $compareTo->toOtherUnit($this->unit);

        return ! compare_float($this->value, $inCurrentUnit->value);
    }
}
