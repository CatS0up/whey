@props([
    'id',
    'label',
    'name',
    'value',
    'invalid' => false,
    'required' => false,
    'checked' => false,
])
<div {{ $attributes
        ->only('class')
        ->merge([
            'class' => 'flex items-center',
        ])  }}>
    <input {{ $attributes->except('class') }}
        id="{{ $id }}"
        name="{{ $name }}"
        value="{{ $value }}"
        type="radio"
        class="cursor-pointer"
        @checked($checked)>
    <x-shared.form.label for="{{ $id }}" @class(['text-red-400' => $invalid, 'ml-2'])>{{ $label . ($required ? ' *' : '')}}</x-shared.form.label>
</div>
