@props([
    'id',
    'label',
    'name',
    'invalid' => false,
    'required' => false,
])
<div {{ $attributes->class([
    'flex flex-row items-center',
])->except('wire:model') }}>
    <input {{ $attributes->whereStartsWith('wire:model') }}
        id="{{ $id }}"
        name="{{ $name }}"
        type="checkbox"
        class="cursor-pointer">
    <x-shared.form.label for="{{ $id }}" @class(['text-red-400' => $invalid, 'ml-2'])>{{ $label . ($required ? ' *' : '')}}</x-shared.form.label>
</div>
