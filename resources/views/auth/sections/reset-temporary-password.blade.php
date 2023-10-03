@extends('layout.auth.layout')

@section('subtitle', ' - reset hasła')

@section('content')
<header>
    <x-shared.rounded-icon icon="fa-solid fa-key" class="mx-auto" />

    <x-auth.form.title>Reset hasła</x-auth.form.title>
</header>
<form action="{{ route('auth.temporaryPassword.reset.request') }}" method="post">
    @csrf

    <div class="mt-6">
        <x-shared.form.input id="password" name="password" type="password" label="Hasło" :invalid="$errors->has('password')" required />
    </div>
    <div class="mt-6">
        <x-shared.form.input id="passwordConfirmation" name="password_confirmation" type="password" label="Potwierdź hasło" required />
    </div>

    <div class="mt-4">
        <x-shared.button type="submit" class="w-full">Zmień hasło</x-shared.button>
    </div>
</form>

<section class="mt-4">
    <form action="{{ route('auth.logout') }}" method="post">
        @csrf
        <x-shared.button type="submit" variant="transparent" class="w-full">Wyloguj</x-shared.button>
    </form>
</section>
@endsection
