@props([
    'dropdownPosition' => 'bottom-left',
])

<div class="flex items-center">
    <div class="flex items-center mr-4">
        <div class="rounded-full w-[50px] h-[50px] overflow-hidden">
            <img src="https://ddob.com/uploads/kozimisiana/efda03111493baa792aca6a6be58e8a3.jpg">
        </div>
        <div class="ml-4">
            <p class="font-semibold text-sm">{{ auth()->user()->name }}</p>
            <p class="text-gray-400 text-sm">{{ auth()->user()->email }}</p>
        </div>
    </div>
    <x-shared.dropdown position="{{ $dropdownPosition }}">
        <x-slot:trigger>
            <x-shared.circle-button>
                <span class="fa-solid fa-gear text-xl"></span>
            </x-shared.circle-button>
        </x-slot>
        <ul>
            <li>
                <a href="#" class="block flex items-center px-6 py-2 font-semibold group transition-colors focus:bg-gray-50 hover:bg-gray-50 outline-none">
                <span class="mr-4 fa-solid fa-user text-green-500"></span>
                <span class="text-gray-400 transition-colors group-focus:text-green-400 group-hover:text-green-400">Profil</span>
                </a>
            </li>
            <li>
                <a href="#" class="block flex items-center px-6 py-2 font-semibold group transition-colors focus:bg-gray-50 hover:bg-gray-50 outline-none">
                <span class="mr-4 fa-solid fa-envelope text-blue-400"></span>
                <span class="text-gray-400 transition-colors group-focus:text-green-400 group-hover:text-green-400">Powiadomienia</span>
                </a>
            </li>
            <li x-data="{
                logout() {
                    $refs.form.submit();
                }
            }">
                <form action="{{ route('auth.logout') }}" method="post" class="hidden" x-ref="form">
                    @csrf
                </form>
                <a @click.prevent="logout" href="#" type="submit" class="block flex items-center px-6 py-2 font-semibold group transition-colors focus:bg-gray-50 hover:bg-gray-50 outline-none">
                    <span class="mr-4 fa-solid fa-arrow-right-from-bracket text-red-400"></span>
                    <span class="text-gray-400 transition-colors group-focus:text-green-400 group-hover:text-green-400">Wyloguj</span>
                </a>
            </li>
        </ul>
    </x-shared.dropdown>
</div>
