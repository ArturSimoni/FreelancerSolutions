<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Perfil') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Formulário de perfil padrão (será mantido como está) --}}
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- Formulário de atualização de senha (será mantido como está) --}}
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- Formulário específico do Perfil de Freelancer (ESTA É A SEÇÃO ATUALIZADA) --}}
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg">
                <div class="max-w-3xl mx-auto">
                    <section>
                        <header>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                Perfil Público de Freelancer
                            </h2>
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                {{ __("Atualize as informações que os clientes verão no seu perfil. Um perfil completo e bem escrito aumenta suas chances de ser contratado.") }}
                            </p>
                        </header>

                        <form method="post" action="{{ route('freelancer.profile.update') }}" class="mt-8 space-y-6">
                            @csrf
                            @method('put')

                            {{-- Layout em Grid para os campos --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                                <div class="md:col-span-2">
                                    <x-input-label for="bio" :value="__('Biografia / Sobre Mim')" />
                                    <textarea id="bio" name="bio" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full" rows="5" placeholder="Fale sobre sua experiência, suas especialidades e o que você pode oferecer aos clientes...">{{ old('bio', $perfilFreelancer->bio) }}</textarea>
                                    <x-input-error class="mt-2" :messages="$errors->get('bio')" />
                                </div>

                                <div>
                                    <x-input-label for="preco_hora" :value="__('Preço por Hora (Opcional)')" />
                                     <div class="relative mt-1">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                            <span class="text-gray-500 sm:text-sm">R$</span>
                                        </div>
                                        <x-text-input id="preco_hora" name="preco_hora" type="number" step="0.01" class="pl-10 block w-full" :value="old('preco_hora', $perfilFreelancer->preco_hora)" min="0" placeholder="50.00" />
                                    </div>
                                    <x-input-error class="mt-2" :messages="$errors->get('preco_hora')" />
                                </div>

                                <div>
                                    <x-input-label for="portifolio_url" :value="__('URL do Portfólio (Opcional)')" />
                                    <div class="relative mt-1">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                            <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244" /></svg>
                                        </div>
                                        <x-text-input id="portifolio_url" name="portifolio_url" type="url" class="pl-10 block w-full" :value="old('portifolio_url', $perfilFreelancer->portifolio_url)" placeholder="https://meu-portfolio.com" />
                                    </div>
                                    <x-input-error class="mt-2" :messages="$errors->get('portifolio_url')" />
                                </div>

                                <div class="md:col-span-2">
                                    <x-input-label :value="__('Habilidades Principais')" />

                                    @if ($perfilFreelancer->habilidades->isNotEmpty())
                                        <div class="p-3 mt-1 bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700 rounded-lg flex flex-wrap gap-2">
                                            @foreach ($perfilFreelancer->habilidades as $habilidade)
                                                <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-primary-100 text-primary-800 dark:bg-primary-800/50 dark:text-primary-300">
                                                    {{ $habilidade->nome }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif

                                    <p class="mt-2 text-sm text-gray-500">Aperte `Ctrl` (ou `Cmd` no Mac) para selecionar ou desmarcar múltiplas habilidades na lista abaixo.</p>
                                    <select id="habilidades" name="habilidades[]" multiple class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full" size="8">
                                        @foreach ($habilidadesDisponiveis as $habilidade)
                                            <option value="{{ $habilidade->id }}" @selected($perfilFreelancer->habilidades->contains($habilidade))>
                                                {{ $habilidade->nome }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <x-input-error class="mt-2" :messages="$errors->get('habilidades')" />
                                </div>

                            </div>

                            <div class="flex items-center gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                                <x-primary-button>{{ __('Salvar Alterações') }}</x-primary-button>

                                @if (session('status') === 'profile-updated')
                                    <p
                                        x-data="{ show: true }"
                                        x-show="show"
                                        x-transition
                                        x-init="setTimeout(() => show = false, 3000)"
                                        class="text-sm text-green-600 dark:text-green-400"
                                    >{{ __('✔ Salvo com sucesso!') }}</p>
                                @endif
                            </div>
                        </form>
                    </section>
                </div>
            </div>

            {{-- Formulário de exclusão de conta (será mantido como está) --}}
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
