<nav x-data="{
    isActive: false,

    toggle() {
        this.isActive = !this.isActive;
    },

    adjustPosition() {
        this.isActive = window.innerWidth >= 1024
    }
}"
        x-show="isActive"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        @toggle-sidebar.window="toggle"
        @resize.window="adjustPosition"
        @load.window="adjustPosition"
        class="fixed z-50 lg:static lg:translate-x-0 max-w-[300px] inset-0 flex flex-col p-8 bg-white overflow-y-scroll shadow">
    <x-dashboard.sidebar.toggler class="ml-auto"/>
    <section class="mb-24">
         <ul>
            <li class="mt-6">
                <x-dashboard.sidebar.subheader>Dashboard</x-dashboard.sidebar.subheader>
                <ul class="mt-2">
                    <li>
                        <x-dashboard.sidebar.link  icon="fa-solid fa-chalkboard-user" target="#">
                            <x-dashboard.sidebar.text>Strona główna</x-dashboard.sidebar.text>
                        </x-dashboard.sidebar.link>
                    </li>
                    <li class="mt-2">
                        <x-dashboard.sidebar.link  icon="fa-solid fa-signal" target="#">
                            <x-dashboard.sidebar.text>Statystyki</x-dashboard.sidebar.text>
                        </x-dashboard.sidebar.link>
                    </li>
                    <li class="mt-2">
                        <x-dashboard.sidebar.link  icon="fa-solid fa-bullseye" target="#">
                            <x-dashboard.sidebar.text>Moje cele</x-dashboard.sidebar.text>
                        </x-dashboard.sidebar.link>
                    </li>
                </ul>
            </li>
            <li class="mt-6">
                <x-dashboard.sidebar.subheader>Ja</x-dashboard.sidebar.subheader>
                <ul class="mt-2">
                    <li>
                        <x-dashboard.sidebar.link  icon="fa-solid fa-user" target="#">
                            <x-dashboard.sidebar.text>Profil</x-dashboard.sidebar.text>
                        </x-dashboard.sidebar.link>
                    </li>
                    <li class="mt-2">
                        <x-dashboard.sidebar.link  icon="fa-solid fa-gear" target="#">
                            <x-dashboard.sidebar.text>Ustawienia</x-dashboard.sidebar.text>
                        </x-dashboard.sidebar.link>
                    </li>
                    <li class="mt-2">
                        <x-dashboard.sidebar.link  icon="fa-solid fa-bell" target="#">
                            <x-dashboard.sidebar.text>Powiadomienia</x-dashboard.sidebar.text>
                            <x-shared.badge class="ml-auto" type="danger">20</x-shared.badge>
                        </x-dashboard.sidebar.link>
                    </li>
                    <li class="mt-2">
                        <x-dashboard.sidebar.link  icon="fa-solid fa-envelope" target="#">
                            <x-dashboard.sidebar.text>Wiadomości</x-dashboard.sidebar.text>
                            <x-shared.badge class="ml-auto">20</x-shared.badge>
                        </x-dashboard.sidebar.link>
                    </li>
                </ul>
            </li>
            <li class="mt-6">
                <x-dashboard.sidebar.subheader>Społeczność</x-dashboard.sidebar.subheader>
                <ul class="mt-2">
                    <li>
                        <x-dashboard.sidebar.link  icon="fa-solid fa-chalkboard-user" target="#">
                            <x-dashboard.sidebar.text>Tablica</x-dashboard.sidebar.text>
                        </x-dashboard.sidebar.link>
                    </li>
                </ul>
            </li>
            <li class="mt-6">
                <x-dashboard.sidebar.subheader>Trening</x-dashboard.sidebar.subheader>
                <ul class="mt-2">
                    <li>
                        <x-dashboard.sidebar.link  icon="fa-solid fa-person-running" target="#">
                            <x-dashboard.sidebar.text>Ćwiczenia</x-dashboard.sidebar.text>
                        </x-dashboard.sidebar.link>
                    </li>
                    <li class="mt-2">
                        <x-dashboard.sidebar.link  icon="fa-sharp fa-solid fa-list-check" target="#">
                            <x-dashboard.sidebar.text>Plany treningowe</x-dashboard.sidebar.text>
                        </x-dashboard.sidebar.link>
                    </li>
                </ul>
            </li>
            <li class="mt-6">
                <x-dashboard.sidebar.subheader>Dieta</x-dashboard.sidebar.subheader>
                <ul class="mt-2">
                    <li>
                        <x-dashboard.sidebar.link  icon="fa-solid fa-bag-shopping" target="#">
                            <x-dashboard.sidebar.text>Produkty</x-dashboard.sidebar.text>
                        </x-dashboard.sidebar.link>
                    </li>
                    <li class="mt-2">
                        <x-dashboard.sidebar.link  icon="fa-solid fa-utensils" target="#">
                            <x-dashboard.sidebar.text>Przepisy</x-dashboard.sidebar.text>
                        </x-dashboard.sidebar.link>
                    </li>
                </ul>
            </li>
        </ul>
    </section>
    <div class="relative mt-auto p-4 m-2 mt-auto bg-gradient-to-r from-cyan-500 to-blue-500 rounded-lg text-blue-100">
        <img src="{{ Vite::asset('resources/images/kettlebell.png') }}" class="absolute -top-[38%] left-[5%]">
        <div class="mt-[30%]">
            <h2 class="font-semibold text-lg">Zacznij planować swój trening</h2>
            <a href="#" class="text-sm">Sprawdź dostępne plany treningowe
            <i class="fa-regular fa-chevron-right"></i>
            </a>
        </div>
    </div>
</nav>
