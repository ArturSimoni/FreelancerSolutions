<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gray-50 px-4 py-12">
        <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8">
            <h2 class="text-3xl font-semibold text-primary-700 mb-6 text-center font-sans">Entrar na sua conta</h2>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Email')" class="text-gray-700 font-medium" />
                    <x-text-input
                        id="email"
                        class="block mt-1 w-full rounded-md border border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-300 focus:ring-opacity-50"
                        type="email"
                        name="email"
                        :value="old('email')"
                        required
                        autofocus
                        autocomplete="username"
                    />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-secondary-600" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Senha')" class="text-gray-700 font-medium" />
                    <x-text-input
                        id="password"
                        class="block mt-1 w-full rounded-md border border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-300 focus:ring-opacity-50"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                    />
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-secondary-600" />
                </div>

                <!-- Remember Me -->
                <div class="block mt-4">
                    <label for="remember_me" class="inline-flex items-center cursor-pointer text-gray-700 select-none">
                        <input
                            id="remember_me"
                            type="checkbox"
                            class="rounded border-gray-300 text-primary-600 shadow-sm focus:ring focus:ring-primary-300 focus:ring-opacity-50"
                            name="remember"
                        />
                        <span class="ml-2 text-sm text-gray-700">Lembrar-me</span>
                    </label>
                </div>

                <!-- Buttons & Links -->
                <div class="flex items-center justify-between mt-6">
                    @if (Route::has('password.request'))
                        <a
                            href="{{ route('password.request') }}"
                            class="text-sm text-secondary-600 hover:text-secondary-800 underline focus:outline-none focus:ring-2 focus:ring-secondary-300 focus:ring-opacity-50 rounded"
                        >
                            Esqueceu sua senha?
                        </a>
                    @endif

                    <button
                        type="submit"
                        class="ml-3 px-6 py-2 rounded-md bg-gradient-to-r from-primary-400 to-primary-600 text-white font-semibold shadow-md hover:from-primary-500 hover:to-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-400 focus:ring-opacity-75 transition"
                    >
                        Entrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
