<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="min-h-screen">
    @include('layout.common.head')
    <body class="antialiased bg-gray-50 h-screen text-gray-900">
        <div class="flex min-h-screen">
                @include('layout.dashboard.partials.sidebar')
            <div class="flex flex-col grow">
                @include('layout.dashboard.partials.header')
            <main class="p-6 grow">
                @yield('content')
            </main>
                @include('layout.dashboard.partials.footer')
            </div>
        </div>
        @include('layout.common.js')
        @stack('scripts')
    </body>
</html>
