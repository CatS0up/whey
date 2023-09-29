@props([
    'field',
])

@error($field)
    <small {{ $attributes->class(['text-red-400 text-xs font-semibold']) }}>
        {{-- Message id to translation --}}
        {{ __($message) }}
    </small>
@enderror
