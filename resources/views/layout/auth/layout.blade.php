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
                    <x-auth.form.container>
                        @yield('content')
                    </x-auth.form.container>
                </div>
            </section>
        </main>
        @include('layout.auth.subviews.footer')
    </div>
    @include('layout.common.js')
    @stack('scripts')
</body>

</html>
