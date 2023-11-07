@extends('layout.dashboard.layout')

@section('subtitle', ' - weryfikacja ćwiczenia')

@section('content')
<x-shared.card class="py-8 px-12 sm:px-24 md:px-32 lg:px-12 rounded-none lg:rounded-2xl">
    <header class="pb-3 mb-4 border-b">
        <h3 class="font-semibold text-xl">Weryfikacja ćwiczenia: {{ $model['exercise']->name }}</h3>
    </header>

    <article>
        <section class="flex">
            <div class="w-[500px] h-[500px] rounded-xl overflow-hidden">
                <img src="{{  $model['exercise_thumbnail_path'] }}" alt="{{ "{$model['exercise']->name} thumbnail" }}">
            </div>

            <div class="ml-4">
                <x-shared.details-property-info title="Autor">
                    <x-shared.link>{{ $model['exercise']->author->name }}</x-shared.link>
                </x-shared.details-property-info>

                <x-shared.details-property-info title="Nazwa">
                    {{ $model['exercise']->name }}
                </x-shared.details-property-info>

                <x-shared.details-property-info title="Poziom trudności" class="mt-2">
                    {{ $model['exercise']->difficulty_level->label() }}
                </x-shared.details-property-info>

                <x-shared.details-property-info title="Rodzaj ćwiczenia" class="mt-2">
                    {{ $model['exercise']->type->label() }}
                </x-shared.details-property-info>

                <x-shared.details-property-info title="Zaangażowane mięśnie" class="mt-2">
                     {{ $model['exercise_muscles']->isNotEmpty() ? $model['exercise_muscles']->pluck('name')->join(', ') : '-- brak --' }}
                </x-shared.details-property-info>
            </div>
        </section>

        <section class="mt-4">
            <x-shared.details-property-info title="Instrukcje" class="mt-2">
                <span class="leading-loose">
                    {!! $model['exercise']->instructions_html !!}
                </span>
            </x-shared.details-property-info>
        </section>
    </article>
</x-shared.card>

<x-shared.card class="mt-6 py-8 px-12 sm:px-24 md:px-32 lg:px-12 rounded-none lg:rounded-2xl">
    <livewire:exercise.exercise-review-form :exerciseId="$model['exercise']->id"/>
</x-shared.card>
@endsection
