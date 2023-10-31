<?php

declare(strict_types=1);

namespace App\Http\Livewire\Shared\Form;

use App\ValueObjects\Form\SelectOption;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use InvalidArgumentException;
use Livewire\Component;

class MultiSelect extends Component
{
    public string $label;
    // TODO: Tłumaczneia
    public string $placeholder = 'Wybierz...';

    /** @var Collection<SelectOption> */
    public Collection $allOptions;
    /** @var Collection<SelectOption> */
    public Collection $choosenOptions;
    /** @var Collection<SelectOption> */
    public Collection $availableOptions;

    public string $search = '';

    public function mount(
        Collection $options,
        array $initialOptions = [],
    ): void {
        if (Arr::isAssoc($initialOptions)) {
            // TODO: Tłumaczenia
            throw new InvalidArgumentException('The initial options array should consist of a basic array of values');
        }

        $this->choosenOptions = collect([]);

        $this->allOptions = $options
            ->sortBy(fn (SelectOption $option) => $option->label)
            ->values();

        $this->availableOptions = $options
            ->sortBy(fn (SelectOption $option) => $option->label)
            ->values();

        array_walk(
            $initialOptions,
            function (string|int|float $option): void {
                $this->select((string) $option);
            },
        );
    }

    public function hydrate(): void
    {
        $this->allOptions = $this->rebuildOptionsCollection($this->allOptions);
        $this->choosenOptions = $this->rebuildOptionsCollection($this->choosenOptions);
        $this->availableOptions = $this->rebuildOptionsCollection($this->availableOptions);
    }

    public function select(string $optionUniqueValue): void
    {
        $found = $this->allOptions->first(fn (SelectOption $option): bool => (string) $option->value === $optionUniqueValue);

        $this->choosenOptions = $this->choosenOptions
            ->push($found)
            ->sortBy(fn (SelectOption $option) => $option->label)
            ->values();

        $this->availableOptions = $this->availableOptions->reject(fn (SelectOption $option): bool => (string) $option->value === $optionUniqueValue);

        $this->emitUp('select', $this->choosenOptions->map(fn (SelectOption $option) => $option->value));
    }

    public function unselect(string $optionUniqueValue): void
    {
        $found = $this->allOptions->first(fn (SelectOption $option): bool => (string) $option->value === $optionUniqueValue);

        $this->availableOptions = $this->availableOptions
            ->push($found)
            ->sortBy(fn (SelectOption $option) => $option->label)
            ->values();

        $this->choosenOptions = $this->choosenOptions->reject(fn (SelectOption $option): bool => (string) $option->value === $optionUniqueValue);

        $this->emitUp('select', $this->choosenOptions->map(fn (SelectOption $option) => $option->value));
    }

    public function updatedSearch(): void
    {
        $phrase = str($this->search)->lower()->trim();

        if ( ! $phrase->isEmpty()) {
            $this->availableOptions = $this->allOptions->filter(
                fn (SelectOption $option): bool => str($option->label)->lower()->startsWith($phrase),
            );
        } else {
            $this->availableOptions = $this->allOptions;
        }
    }

    public function render(): View
    {
        return view('livewire.shared.form.multi-select');
    }

    /** SelectOption are convert to array after first render, we want use Value OBject instead array */
    private function rebuildOptionsCollection(Collection $options): Collection
    {
        return $options->map(
            fn (array $option): SelectOption => new SelectOption(data_get($option, 'value'), data_get($option, 'label')),
        )
            ->sortBy(fn (SelectOption $option) => $option->label)
            ->values();
    }
}
