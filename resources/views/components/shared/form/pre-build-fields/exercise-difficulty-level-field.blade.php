<x-shared.form.select.input {{ $attributes }}>
    @foreach (\App\Enums\DifficultyLevel::cases() as $level)
    <x-shared.form.select.option value="{{ $level->value }}">
        {{ $level->label() }}
    </x-shared.form.select.option>
    @endforeach
</x-shared.form.select.input>
