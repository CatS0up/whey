<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('layout.common.head')

<body class="antialiased bg-gray-50 h-screen text-gray-900">
        <div class="flex flex-col h-full max-h-screen">
            @include('layout.auth.subviews.header')
            <main class="flex flex-col w-full max-w-[1080px] mx-auto grow">
                <section class="lg:mx-6 lg:my-auto">
                    <div class="lg:flex lg:items-center">
                        @include('layout.auth.subviews.hello-section')
                        <section class="w-full lg:w-1/2 lg:pl-8">
                            <x-shared.card class="py-12 px-12 sm:px-24 md:px-32 lg:px-12 rounded-none lg:rounded-2xl">
                                @yield('content')
                            </x-shared.card>
                        </section>
                    </div>
                </section>
            </main>
            @include('layout.auth.subviews.footer')
        </div>
        @include('layout.common.js')
        @stack('scripts')
    </body>
</html>
