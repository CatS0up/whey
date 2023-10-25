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
            <div @class([
                'transition-colors rounded-full',
                'bg-green-200 group-hover:bg-green-400 group-focus:bg-green-400' => 'green' === $color,
                'bg-indigo-200 group-hover:bg-indigo-400 group-focus:bg-indigo-400' => 'indigo' === $color,
                'bg-gray-200 group-hover:bg-gray-400 group-focus:bg-gray-400' => 'gray' === $color,
            ])>
                <img src="{{ asset('images/icons/'.$role->value.'.png') }}" alt="{{ $role->label() }}" class="p-4">
            </div>
            <figcaption class="mt-2 text-center font-semibold"> {{ $role->label() }} </figcaption>
        </figure>
    </div>
</div>
