<div>
    <header>
        {{ $header }}

        <x-shared.stepper.container class="mt-4">
            {{ $stepper }}
        </x-shared.stepper.container>
    </header>
    <form wire:submit.prevent="submit" class="mt-6">
        @csrf

        <section>
            {{ $body }}
        </section>

        <section class="mt-4">
            {{ $buttons }}
        </section>
    </form>
</div>
