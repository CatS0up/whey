<x-shared.form.select.input {{ $attributes }}>
    @foreach (\App\Enums\PhoneCountry::cases() as $country)
    <x-shared.form.select.option value="{{ $country->value }}">
        {{ $country->label() }}
    </x-shared.form.select.option>
    @endforeach
</x-shared.form.select.input>
