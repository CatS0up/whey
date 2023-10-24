@extends('layout.auth.layout')

@section('subtitle', ' - wybierz role')

@section('content')
<header>
    <x-shared.rounded-icon icon="fa-solid fa-key" class="mx-auto" />

    <x-auth.form.title>Choose your fighter</x-auth.form.title>
</header>

<section class="flex justify-between items-center mt-6">
    <div>
        <x-auth.demo-user-role-selector :role="\App\Enums\Role::User" color="green"/>
    </div>
    <div>
        <x-auth.demo-user-role-selector :role="\App\Enums\Role::Trainer" color="indigo"/>
    </div>
    <div>
        <x-auth.demo-user-role-selector :role="\App\Enums\Role::Admin" color="gray"/>
    </div>
</section>

<section class="mt-4">
    <x-auth.form.back-to-login-page-button />
</section>
@endsection
