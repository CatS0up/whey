<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('layout.common.head')

<body class="antialiased bg-gray-50 h-screen text-gray-900">
    <div class="flex flex-col h-full max-h-screen">
        @include('layout.auth.subviews.header')
        <main class="flex flex-col justify-center items-center w-full max-w-[1080px] mx-auto grow">
            <x-auth.form.container>
                <header>
                    <x-shared.rounded-icon icon="fa-solid fa-lock" class="mx-auto" />
                    <x-auth.form.title>Potwierdź hasło</x-auth.form.title>
                </header>
                        <form action="{{ route('auth.confirmPassword.request') }}" method="post">
                            @csrf
                            <div class="mt-6">
                                <x-shared.form.input
                                    wire:model.defer="password"
                                    id="password"
                                    name="password"
                                    type="password"
                                    label="Hasło"
                                    :invalid="$errors->has('password')"
                                    required/>

                                    <x-shared.form.error field="password"/>
                            </div>
                            <div class="mt-4">
                                <x-shared.button type="submit" class="w-full">Potwierdź</x-shared.button>
                            </div>
                        </form>
            </x-auth.form.container>
        </main>
        @include('layout.auth.subviews.footer')
    </div>
    @include('layout.common.js')
</body>
</html>
