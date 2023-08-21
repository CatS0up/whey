@push('styles')
    @once
        @vite([
            'node_modules/filepond/dist/filepond.min.css',
            'node_modules/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css',
        ])
    @endonce
@endpush

@props([
    'multiple' => false,
    'preview' => true,
    'previewMaxHeight' => 200,
    'initialFiles' => [],
])

<div wire:ignore>
    <input x-data x-init="$nextTick(() => {
            FilePond.registerPlugin(FilePondPluginImagePreview);

            const post = FilePond.create(
                $refs.input,
                    {
                        files: @js($initialFiles).map(path => ({ source: path })),
                    },
                );

            post.setOptions({
                allowMultiple: {{ $multiple ? 'true' : 'false' }},
                allowImagePreview: {{ $preview ? 'true' : 'false' }},
                imagePreviewMaxHeight: {{ $previewMaxHeight }},
                server: {
                    process:(fieldName, file, metadata, load, error, progress, abort, transfer, options) => {
                        @this.upload('{{ $attributes->whereStartsWith('wire:model')->first() }}', file, load, error, progress)
                    },
                    revert: (filename, load) => {
                        @this.removeUpload('{{ $attributes->whereStartsWith('wire:model')->first() }}', filename, load)
                    },
                },
            });
        })" x-ref="input" type="file" {{ $attributes }} />
</div>

@push('scripts')
    @once
        @vite([
            'node_modules/filepond/dist/filepond.min.js',
            'node_modules/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.js',
        ])
    @endonce
@endpush
