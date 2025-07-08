<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Usuário') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                {{ __('Informações do Usuário') }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-600">
                                {{ __("Atualize o nome, email e perfil do usuário.") }}
                            </p>
                        </header>

                        <form method="post" action="{{ route('admin.users.update', $user) }}" class="mt-6 space-y-6">
                            @csrf
                            @method('patch')

                            <!-- Nome -->
                            <div>
                                <x-input-label for="name" :value="__('Nome')" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <!-- Email -->
                            <div>
                                <x-input-label for="email" :value="__('Email')" />
                                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
                                <x-input-error class="mt-2" :messages="$errors->get('email')" />
                            </div>

                            <!-- Perfil -->
                            <div>
                                <x-input-label for="perfil" :value="__('Perfil')" />
                                <select id="perfil" name="perfil" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" required>
                                    <option value="cliente" {{ old('perfil', $user->perfil) === 'cliente' ? 'selected' : '' }}>Cliente</option>
                                    <option value="freelancer" {{ old('perfil', $user->perfil) === 'freelancer' ? 'selected' : '' }}>Freelancer</option>
                                    <option value="administrador" {{ old('perfil', $user->perfil) === 'administrador' ? 'selected' : '' }}>Administrador</option>
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('perfil')" />
                            </div>

                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('Salvar') }}</x-primary-button>

                                @if (session('status') === 'user-updated')
                                    <p
                                        x-data="{ show: true }"
                                        x-show="show"
                                        x-transition
                                        x-init="setTimeout(() => show = false, 2000)"
                                        class="text-sm text-gray-600"
                                    >{{ __('Salvo.') }}</p>
                                @endif
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
