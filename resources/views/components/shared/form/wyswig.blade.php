<div wire:ignore {{ $attributes }}>
    <textarea
        x-data
        x-ref="input"
        x-init="$nextTick(() => {
                class CustomUploadAdapter
                {
                    constructor( loader ) {
                        this.loader = loader;
                    }

                    upload() {
                        return this.loader.file
                            .then( file => new Promise( ( resolve, reject ) => {
                                this._initRequest();
                                this._initListeners( resolve, reject, file );
                                this._sendRequest( file );
                            } ) );
                    }
                    abort() {
                        if ( this.xhr ) {
                            this.xhr.abort();
                        }
                    }

                    _initRequest() {
                        const xhr = this.xhr = new XMLHttpRequest();
                        xhr.open( 'POST', '{{ route('web.media.ckeditorImage.upload') }}', true );
                        xhr.setRequestHeader('x-csrf-token', '{{ csrf_token() }}');
                        xhr.responseType = 'json';
                    }

                    _initListeners( resolve, reject, file ) {
                        const xhr = this.xhr;
                        const loader = this.loader;
                        const genericErrorText = `Couldn't upload file: ${ file.name }.`;

                        xhr.addEventListener( 'error', () => reject( genericErrorText ) );
                        xhr.addEventListener( 'abort', () => reject() );
                        xhr.addEventListener( 'load', () => {
                            const response = xhr.response;
                            if ( !response || response.error ) {
                                return reject( response && response.error ? response.error.message : genericErrorText );
                            }

                            resolve( {
                                default: response.url
                            } );
                        } );

                        if ( xhr.upload ) {
                            xhr.upload.addEventListener( 'progress', evt => {
                                if ( evt.lengthComputable ) {
                                    loader.uploadTotal = evt.total;
                                    loader.uploaded = evt.loaded;
                                }
                            } );
                        }
                    }

                    _sendRequest( file ) {
                        const data = new FormData();

                        data.append( 'upload', file );

                        this.xhr.send( data );
                }
            }

                ClassicEditor
                    .create($refs.input)
                    .then(editor => {
                        editor.model.document.on('change:data', () => {
                            @this.set('{{ $attributes->whereStartsWith('wire:model')->first() }}', editor.getData());
                            @this.emitSelf('ckeditorReady');
                        });

                        editor.plugins.get('FileRepository').createUploadAdapter = ( loader ) => {
                            return new CustomUploadAdapter( loader );
                        }

                        editor.editing.view.change(writer => {
                            writer.setStyle(
                                'min-height',
                                '200px',
                                editor.editing.view.document.getRoot(),
                            );
                        });

                        editor.setData(@this.get('{{ $attributes->whereStartsWith('wire:model')->first() }}') || '');
                })
                .catch(error => {
                    console.error(error);
                })
            })
        ">
    </textarea>
</div>

@push('scripts')
    @once
        @vite(['node_modules/@ckeditor/ckeditor5-build-classic/build/ckeditor.js'])
    @endonce
@endpush
