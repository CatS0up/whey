<div>
    {{-- Choosen options - start --}}
    <div wire:loading.remove class="flex flex-row flex-wrap max-h-[30vh] mt-2 gap-2">
        @forelse ($choosenOptions as $choosenOption)
        <x-shared.form.multi-select.choosen-option
            wire:click="unselect({{ $choosenOption->value }})"
            wire:keydown.enter="unselect({{ $choosenOption->value }})"
            :label="$choosenOption->label"
            />
        @empty
            <span class="font-semibold text-sm text-gray-600">{{ $placeholder }}</span>
        @endforelse
    </div>
    {{-- Choosen options - end --}}

    <div wire:loading class="w-full text-sm text-gray-400 font-semibold">
        Przetwarzanie...
    </div>

    {{-- Available options dropdown - start --}}
    <div class="inline-block mt-2">
        <x-shared.dropdown position="top-right" class="w-[45vw] px-4 py-2">
            <x-slot:trigger>
                <x-shared.circle-button type="button">
                    <span class="fa-solid fa-plus text-xl"></span>
                </x-shared.circle-button>
            </x-slot>
            <section class="mt-2">
                <x-shared.form.input
                    wire:model.debounce="search"
                    wire:loading.attr="disabled"
                    wire:target="select"
                    id="name"
                    name="name"
                    type="search"
                    label="Szukaj..." />
            </section>

            {{-- Available options section - start --}}
            <section wire:loading.remove class="flex flex-row flex-wrap max-h-[30vh] mt-4 overflow-y-scroll gap-4 outline-none">
                @forelse ($availableOptions as $option)
                <x-shared.form.multi-select.available-option
                    wire:click="select({{ $option->value }})"
                    wire:keydown.enter="select({{ $option->value }})"
                    :label="$option->label"/>
                @empty
                    <span class="w-full text-center text-sm text-gray-400 font-semibold">Brak wyników...</span>
                @endforelse
            </section>
            {{-- Available options section - end --}}

            <div wire:loading class="w-full pt-8 pb-4 text-center text-sm text-gray-400 font-semibold">
                Przetwarzanie...
            </div>
        </x-shared.dropdown>
    </div>
    {{-- Available options dropdown - end --}}
</div>
