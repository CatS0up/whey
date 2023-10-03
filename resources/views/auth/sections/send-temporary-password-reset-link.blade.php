@extends('layout.auth.layout')

@section('subtitle', ' - wyślij link do resetu hasła')

@section('content')
<header>
    <x-shared.rounded-icon icon="fa-solid fa-key" class="mx-auto" />

    <x-auth.form.title>Wyślij link do resetu hasła</x-auth.form.title>
</header>
<form action="{{ route('auth.temporaryPassword.send.request') }}" method="post">
    @csrf
    <div class="mt-6">
        <x-shared.form.input
            id="email"
            name="email"
            type="email"
            label="Email"
            value="{{ old('email') }}"
            :invalid="$errors->has('email')"
            required/>

            <x-shared.form.error field="email"/>
    </div>

    <div class="mt-4">
        <x-shared.button type="submit" class="w-full">Wyślij</x-shared.button>
    </div>
</form>

<section class="mt-4">
    <x-auth.form.back-to-login-page-button />
</section>
@endsection
