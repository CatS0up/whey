@extends('layout.auth.layout')

@section('content')
<header>
    <x-shared.rounded-icon icon="fa-solid fa-key" class="mx-auto" />

    <x-auth.form.title>Link aktywacyjny wygasł</x-auth.form.title>
</header>

<section>
    <p class="mt-6 text-md">
        Wygląda na to że twój link aktywacyjny wygasł. Aby aktywować konto należy wysłać ponownie wiadomość z linkiem aktywacyjnym.
    </p>
    <a href="{{ route('auth.emailVerify.resend.resend', ['token' => $model['token']->token]) }}" class="group block mt-6 outline-none">
        <x-shared.button variant="primary" type="submit" class="w-full">Wyślij ponownie</x-shared.button>
    </a>
</section>

<section class="mt-4">
        <x-auth.form.back-to-login-page-button/>
</section>
@endsection
