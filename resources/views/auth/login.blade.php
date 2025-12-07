<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Toggle Buttons -->
    <div class="flex mb-6 bg-white/30 rounded-full overflow-hidden border-0">
        <button type="button" 
                onclick="showForm('login')" 
                id="loginBtn"
                class="flex-1 py-3 px-6 text-sm font-medium transition-colors bg-white text-gray-900 rounded-full relative">
            <svg class="inline-block w-4 h-4 mr-2" id="loginCheck" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
            </svg>
            Inicio de sesión
        </button>
        <button type="button" 
                onclick="showForm('register')" 
                id="registerBtn"
                class="flex-1 py-3 px-6 text-sm font-medium transition-colors text-gray-700 rounded-full relative">
            <svg class="inline-block w-4 h-4 mr-2 invisible" id="registerCheck" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
            </svg>
            Registrarse
        </button>
    </div>

    <form method="POST" action="{{ route('login') }}" id="loginForm">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800" name="remember">
                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>

    <form method="POST" action="{{ route('register') }}" id="registerForm" style="display: none;">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="register_email" :value="__('Email')" />
            <x-text-input id="register_email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Dirección -->
        <div class="mt-4">
            <x-input-label for="register_direccion" value="Dirección" />
            <x-text-input id="register_direccion" class="block mt-1 w-full" type="text" name="direccion" :value="old('direccion')" required autocomplete="street-address" />
            <x-input-error :messages="$errors->get('direccion')" class="mt-2" />
        </div>

        <!-- Rol -->
        <div class="mt-4">
            <x-input-label for="register_rol" value="Tipo de Usuario" />
            <select id="register_rol" name="rol" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                <option value="">Selecciona tu rol</option>
                <option value="Administrador" {{ old('rol') == 'Administrador' ? 'selected' : '' }}>Administrador de Eventos</option>
                <option value="Participante" {{ old('rol') == 'Participante' ? 'selected' : '' }}>Participante</option>
            </select>
            <x-input-error :messages="$errors->get('rol')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="register_password" :value="__('Password')" />

            <x-text-input id="register_password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="register_password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="register_password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>

    <script>
        function showForm(formType) {
            const loginForm = document.getElementById('loginForm');
            const registerForm = document.getElementById('registerForm');
            const loginBtn = document.getElementById('loginBtn');
            const registerBtn = document.getElementById('registerBtn');
            const loginCheck = document.getElementById('loginCheck');
            const registerCheck = document.getElementById('registerCheck');

            if (formType === 'login') {
                loginForm.style.display = 'block';
                registerForm.style.display = 'none';
                loginBtn.classList.add('bg-white', 'text-gray-900');
                loginBtn.classList.remove('text-gray-700');
                loginCheck.classList.remove('invisible');
                registerBtn.classList.remove('bg-white', 'text-gray-900');
                registerBtn.classList.add('text-gray-700');
                registerCheck.classList.add('invisible');
            } else {
                loginForm.style.display = 'none';
                registerForm.style.display = 'block';
                registerBtn.classList.add('bg-white', 'text-gray-900');
                registerBtn.classList.remove('text-gray-700');
                registerCheck.classList.remove('invisible');
                loginBtn.classList.remove('bg-white', 'text-gray-900');
                loginBtn.classList.add('text-gray-700');
                loginCheck.classList.add('invisible');
            }
        }
    </script>
</x-guest-layout>
