@props([
    'id' => 'recaptcha',
    'name' => 'recaptcha',
    'action' => 'register',
])

<p x-data x-init="$nextTick(() => {
        grecaptcha.ready(() => {
            grecaptcha.execute('{{ config('services.recaptcha.site_key') }}', { action: {{ $action }} })
            .then(token => {
                $refs.input.value = token;
                @this.set('{{ $attributes->whereStartsWith('wire:model')->first() }}', token)
            });
        });
     })">
    <input x-ref="input" id="{{ $id }}" name="{{ $name }}" type="hidden">
</p>

@once
    @push('scripts')
        <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}" async defer></script>
    @endpush
@endonce
