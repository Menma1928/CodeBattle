@auth
<nav x-data="{ open: false }" class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-md border-b border-purple-100 dark:border-gray-700 sticky top-0 z-50 shadow-sm">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <!-- Logo -->
                <a href="/" class="flex items-center space-x-2 mr-8">
                    <img src="/logo.png" alt="CodeBattle Logo" class="w-8 h-8 rounded-lg object-contain bg-white" />
                    <span class="text-xl font-bold bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent">CodeBattle</span>
                </a>

                <!-- Navigation Links -->
                <div class="hidden space-x-1 sm:flex items-center">
                    <!-- Dashboard Link -->
                    <a href="{{ route('dashboard') }}"
                       class="px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-150 {{ request()->routeIs('dashboard') ? 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300' : 'text-gray-700 dark:text-gray-300 hover:bg-purple-50 dark:hover:bg-purple-900/20' }}">
                        Dashboard
                    </a>

                    @hasrole('Super Admin')
                        <a href="{{ route('equipos.index') }}"
                           class="px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-150 {{ request()->routeIs('equipos.index') ? 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300' : 'text-gray-700 dark:text-gray-300 hover:bg-purple-50 dark:hover:bg-purple-900/20' }}">
                            Equipos
                        </a>
                    @endhasrole

                    @hasrole('Participante')
                        <a href="{{ route('equipos.misEquipos') }}"
                           class="px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-150 {{ request()->routeIs('equipos.misEquipos') ? 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300' : 'text-gray-700 dark:text-gray-300 hover:bg-purple-50 dark:hover:bg-purple-900/20' }}">
                            Mis equipos
                        </a>
                    @endhasrole

                    @hasrole('Administrador')
                        <a href="{{ route('eventos.create') }}"
                           class="px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-150 {{ request()->routeIs('eventos.create') ? 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300' : 'text-gray-700 dark:text-gray-300 hover:bg-purple-50 dark:hover:bg-purple-900/20' }}">
                            Crear Evento
                        </a>
                        <a href="{{ route('eventos.misEventos') }}"
                           class="px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-150 {{ request()->routeIs('eventos.misEventos') ? 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300' : 'text-gray-700 dark:text-gray-300 hover:bg-purple-50 dark:hover:bg-purple-900/20' }}">
                            Mis eventos
                        </a>
                    @endhasrole

                    @can('ver eventos')
                        <a href="{{ route('eventos.index') }}"
                           class="px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-150 {{ request()->routeIs('eventos.index') ? 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300' : 'text-gray-700 dark:text-gray-300 hover:bg-purple-50 dark:hover:bg-purple-900/20' }}">
                            Eventos
                        </a>
                    @endcan
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:space-x-4">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center space-x-2 px-3 py-2 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-purple-50 dark:hover:bg-purple-900/20 transition duration-150">
                            <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-indigo-500 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <span>{{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            Perfil
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                Cerrar Sesión
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-700 dark:text-gray-300 hover:bg-purple-50 dark:hover:bg-purple-900/20 transition duration-150">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white dark:bg-gray-800 border-t border-purple-100 dark:border-gray-700">
        <div class="pt-2 pb-3 space-y-1 px-2">
            <!-- Dashboard Link -->
            <a href="{{ route('dashboard') }}" class="block px-4 py-2 rounded-lg text-sm font-semibold {{ request()->routeIs('dashboard') ? 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300' : 'text-gray-700 dark:text-gray-300 hover:bg-purple-50 dark:hover:bg-purple-900/20' }}">
                Dashboard
            </a>

            @hasrole('Super Admin')
                <a href="{{ route('equipos.index') }}" class="block px-4 py-2 rounded-lg text-sm font-semibold {{ request()->routeIs('equipos.index') ? 'bg-purple-100 text-purple-700' : 'text-gray-700 hover:bg-purple-50' }}">
                    Equipos
                </a>
            @endhasrole

            @hasrole('Participante')
                <a href="{{ route('equipos.misEquipos') }}" class="block px-4 py-2 rounded-lg text-sm font-semibold {{ request()->routeIs('equipos.misEquipos') ? 'bg-purple-100 text-purple-700' : 'text-gray-700 hover:bg-purple-50' }}">
                    Mis equipos
                </a>
            @endhasrole

            @hasrole('Administrador')
                <a href="{{ route('eventos.create') }}" class="block px-4 py-2 rounded-lg text-sm font-semibold {{ request()->routeIs('eventos.create') ? 'bg-purple-100 text-purple-700' : 'text-gray-700 hover:bg-purple-50' }}">
                    Crear Evento
                </a>
                <a href="{{ route('eventos.misEventos') }}" class="block px-4 py-2 rounded-lg text-sm font-semibold {{ request()->routeIs('eventos.misEventos') ? 'bg-purple-100 text-purple-700' : 'text-gray-700 hover:bg-purple-50' }}">
                    Mis eventos
                </a>
            @endhasrole

            @can('ver eventos')
                <a href="{{ route('eventos.index') }}" class="block px-4 py-2 rounded-lg text-sm font-semibold {{ request()->routeIs('eventos.index') ? 'bg-purple-100 text-purple-700' : 'text-gray-700 hover:bg-purple-50' }}">
                    Eventos
                </a>
            @endcan
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-purple-100 dark:border-gray-700">
            <div class="px-4 pb-2">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-indigo-500 rounded-full flex items-center justify-center text-white font-semibold">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</div>
                    </div>
                </div>
            </div>

            <div class="mt-3 space-y-1 px-2">
                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-purple-50 dark:hover:bg-purple-900/20">
                    Perfil
                </a>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-purple-50 dark:hover:bg-purple-900/20">
                        Cerrar Sesión
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
@endauth
