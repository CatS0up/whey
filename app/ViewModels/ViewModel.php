<?php

declare(strict_types=1);

namespace App\ViewModels;

use Illuminate\Contracts\Support\Arrayable;
use ReflectionClass;
use Reflection;
use ReflectionMethod;

abstract class ViewModel implements Arrayable
{
    public function toArray()
    {
        return collect((new ReflectionClass($this))->getMethods())
            ->reject(
                fn (ReflectionMethod $method): bool =>
            in_array(
                $method->getName(),
                ['__construct', 'toArray'],
            )
            )
            ->filter(
                fn (ReflectionMethod $method): bool =>
            in_array(
                'public',
                Reflection::getModifierNames(
                    $method->getModifiers(),
                ),
            ),
            )
            ->mapWithKeys(fn (ReflectionMethod $method): array => [
                str()->snake($method->getName()) => $this->{$method->getName()}(),
            ])
            ->all();
    }
}
