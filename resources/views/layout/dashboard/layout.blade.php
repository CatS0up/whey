<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="min-h-screen">
    @include('layout.common.head')
    <body class="antialiased h-screen">
        <div class="flex min-h-screen">
                @include('layout.dashboard.subviews.sidebar')
            <div class="flex flex-col grow">
                @include('layout.dashboard.subviews.header')
            <main class="p-6 bg-green-50 grow">
                @yield('content')
            </main>
                @include('layout.dashboard.subviews.footer')
            </div>
        </div>
        @include('layout.common.js')
        @stack('scripts')
    </body>
</html>
