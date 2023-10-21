@props([
    'id' => 'recaptcha',
    'name' => 'recaptcha',
    'action' => 'register',
])

<p x-data x-init="$nextTick(() => {
        const parentForm = $refs.input.closest('form');

        if (! parentForm)
        {
            throw new Error('The given input does not have a parent form');
        }

        parentForm.addEventListener('submit', e => {
            e.preventDefault();
            grecaptcha.ready(() => {
                grecaptcha.execute('{{ config('services.recaptcha.site_key') }}', { action: @js($action) })
                .then(token => {
                    $refs.input.value = token;
                    @this.set('{{ $attributes->whereStartsWith('wire:model')->first() }}', token);
                    @this.emitSelf('recaptcha-ready');
                });
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
