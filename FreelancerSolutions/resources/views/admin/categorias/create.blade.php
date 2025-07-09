<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            {{ __('Criar Nova Categoria') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 sm:p-8 text-gray-900 dark:text-gray-100">
                    <header>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            Informações da Categoria
                        </h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Preencha os dados para criar uma nova categoria para os projetos.
                        </p>
                    </header>

                    <form action="{{ route('admin.categorias.store') }}" method="POST" class="mt-6 space-y-6">
                        @csrf

                        {{-- Nome da Categoria --}}
                        <div>
                            <x-input-label for="nome" :value="__('Nome da Categoria')" />
                            <x-text-input id="nome" name="nome" type="text" class="mt-1 block w-full" :value="old('nome')" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('nome')" />
                        </div>

                        {{-- Descrição --}}
                        <div>
                            <x-input-label for="descricao" :value="__('Descrição (Opcional)')" />
                            <textarea id="descricao" name="descricao" rows="4" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full">{{ old('descricao') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('descricao')" />
                        </div>

                        {{-- Botões --}}
                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Salvar Categoria') }}</x-primary-button>
                            <a href="{{ route('admin.categorias.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
