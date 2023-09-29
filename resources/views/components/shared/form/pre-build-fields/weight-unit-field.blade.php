<x-shared.form.select.input {{ $attributes }}>
    @foreach (\App\Enums\WeightUnit::cases() as $unit)
    <x-shared.form.select.option value="{{ $unit->value }}">
        {{ $unit->label() }}
    </x-shared.form.select.option>
    @endforeach
</x-shared.form.select.input>
