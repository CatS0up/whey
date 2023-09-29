<div>
    <div {{ $attributes->class(['flex']) }}>
        <div class="flex items-stretch">
            {{ $selectInput }}
        </div>
        <div class="ml-4 grow">
            {{ $mainInput }}
        </div>
    </div>

    <div>
        {{ $errors }}
    </div>
</div>
