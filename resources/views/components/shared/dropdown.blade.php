@props([
'position' => 'bottom-center',
])

<div x-data="{
    isOpen: false,

    toggle() {
        this.isOpen = !this.isOpen;
    },

    close() {
        this.isOpen = false;
    }
}" class="relative">
    <div @click="toggle" @keyup.escape.window="close" class="cursor-pointer" data-index="-1">
        {{ $trigger }}
    </div>

    <div @click.away="close" x-show="isOpen" {{ $attributes->class([
        'absolute py-4 bg-white rounded-lg shadow overflow-hidden z-50',
        'bottom-full right-full mb-2' => 'top-left' === $position,
        'bottom-full left-1/2 mb-2 -translate-x-1/2' => 'top-center' === $position,
        'bottom-full left-full mb-2' => 'top-right' === $position,
        'top-1/2 right-full mr-2 -translate-y-1/2' => 'center-left' === $position,
        'top-1/2 left-full ml-2 -translate-y-1/2' => 'center-right' === $position,
        'top-full right-full mt-2' => 'bottom-left' === $position,
        'top-full left-1/2 mt-2 -translate-x-1/2' => 'bottom-center' === $position,
        'top-full left-full mt-2' => 'bottom-right' === $position,
        ]) }}>
        {{ $slot }}
    </div>
</div>
