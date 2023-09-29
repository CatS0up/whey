<div {{ $attributes->class([
    'relative',
    ]) }}>
    <div class="absolute top-1/2 inset-x-0 h-px bg-gray-300">
    </div>
    <div class="relative flex justify-between items-center">
        {{ $slot }}
    </div>
</div>
