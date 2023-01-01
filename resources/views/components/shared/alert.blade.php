<div class="flex items-center p-4 bg-{{ $type->color() }}-50 text-{{ $type->color() }}-500 border border-{{ $type->color() }}-500 rounded">
    <span>{{ $message }}</span>

    <button class="group block ml-auto text-{{ $type->color() }}-500 focus:outline-none">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 group-focus:text-{{ $type->color() }}-900 group-hover:text-{{ $type->color() }}-900 transition-colors">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
</div>
