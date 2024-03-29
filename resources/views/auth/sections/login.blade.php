@extends('layout.auth.layout')

@section('subtitle', ' - logowanie')

@section('content')
<header>
    <x-shared.rounded-icon icon="fa-solid fa-key" class="mx-auto" />

    <x-auth.form.title>Logowanie</x-auth.form.title>
</header>
<form action="{{ route('auth.login.request') }}" method="post">
    @csrf

    <div class="mt-6">
        <x-shared.form.input
            id="login"
            name="login"
            type="login"
            label="Numer telefonu lub e-mail"
            value="{{ old('login') }}"
            :invalid="$errors->has('login')"
            required />

            <x-shared.form.error field="login"/>
    </div>
    <div class="mt-6">
        <x-shared.form.input
            id="password"
            name="password"
            type="password"
            label="Hasło"
            :invalid="$errors->has('password')"
            required />

            <x-shared.form.error field="password"/>
    </div>
    <div class="mt-6">
        <x-shared.form.checkbox
            id="rememberMe"
            name="remember_me"
            label="Zapamiętaj mnie"
            :invalid="$errors->has('remember_me')"
            :checked="old('remember_me')"/>

            <x-shared.form.error field="remember_me"/>
    </div>

    <div class="mt-4">
        <x-shared.button type="submit" class="w-full">Zaloguj</x-shared.button>
    </div>
</form>
<section class="mt-4">
    <div class="flex justify-between flex-wrap">
        <x-shared.link href="{{ route('auth.temporaryPassword.send.show') }}">Nie pamiętasz hasła?</x-shared.link>
        <x-shared.link href="{{ route('auth.demoUser.show') }}">Wypróbuj (Demo)</x-shared.link>
    </div>
</section>
@endsection
