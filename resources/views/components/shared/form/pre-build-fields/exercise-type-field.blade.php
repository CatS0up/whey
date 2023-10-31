<x-shared.form.select.input {{ $attributes }}>
    @foreach (\App\Enums\ExerciseType::cases() as $type)
    <x-shared.form.select.option value="{{ $type->value }}">
        {{ $type->label() }}
    </x-shared.form.select.option>
    @endforeach
</x-shared.form.select.input>
