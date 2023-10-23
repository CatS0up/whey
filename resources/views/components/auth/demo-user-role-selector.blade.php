<div x-data="{
    submit()
    {
        $refs.form.submit();
    }
}">
    <form x-ref="form" action="{{ route('auth.demoUser.request', ['role' => $role ]) }}" method="post" class="hidden">
        @csrf
    </form>

    <div @click="submit" @keyup.enter="submit" tabindex="0" class="group outline-none cursor-pointer">
        <figure>
            <div class="bg-{{ $color }}-200 transition-colors group-hover:bg-{{ $color }}-400 group-focus:bg-{{ $color }}-400 rounded-full">
                <img src="{{ asset('images/icons/'.$role->value.'.png') }}" alt="{{ $role->label() }}" class="p-4">
            </div>
            <figcaption class="mt-2 text-center font-semibold"> {{ $role->label() }} </figcaption>
        </figure>
    </div>
</div>
