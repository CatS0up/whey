<?php

declare(strict_types=1);

namespace App\ValueObjects\Shared;

use App\Enums\WeightUnit;
use InvalidArgumentException;
use Stringable;

final readonly class Weight implements Stringable
{
    private const MIN_VALUE = 0;

    private function __construct(
        public float $value,
        public WeightUnit $unit,
    ) {
        if (self::MIN_VALUE > $value) {
            throw new InvalidArgumentException('Value should be greater or equals to '.self::MIN_VALUE);
        }
    }

    public static function fromValueAndUnit(float $value, WeightUnit $unit): self
    {
        return new self($value, $unit);
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

    public function toOtherUnit(WeightUnit $unit): self
    {
        $value = $this->value * $this->unit->otherUnitValue($unit);

        return new self($value, $unit);
    }

    public function equalsTo(Weight $compareTo): bool
    {
        $inCurrentUnit = $compareTo->toOtherUnit($this->unit);

        return compare_float($this->value, $inCurrentUnit->value);
    }

    public function notEqualsTo(Weight $compareTo): bool
    {
        return ! $this->equalsTo($compareTo);
    }
}
