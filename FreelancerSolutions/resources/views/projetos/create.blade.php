<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Publicar Novo Projeto') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">


                <div class="p-6 sm:p-8 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 flex items-center">
                        <svg class="w-8 h-8 mr-3 text-primary-500" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M2 6a6 6 0 1 1 10.174 4.31c-.203.196-.359.4-.453.619l-.762 1.769A.5.5 0 0 1 10.5 13h-5a.5.5 0 0 1-.46-.302l-.761-1.77a1.964 1.964 0 0 0-.453-.618A5.984 5.984 0 0 1 2 6zm6 8.5a.5.5 0 0 1 .5-.5h.5a.5.5 0 0 1 0 1h-.5a.5.5 0 0 1-.5-.5zM8 1a3 3 0 0 0-3 3h6a3 3 0 0 0-3-3z"/>
                        </svg>
                        Detalhes do Novo Projeto
                    </h3>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        Preencha as informações abaixo para encontrar o freelancer perfeito.
                    </p>
                </div>


                <div class="p-6 sm:p-8">
                    <form action="{{ route('projetos.store') }}" method="POST" class="space-y-6">
                        @csrf


                        <div>
                            <label for="titulo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-100">Título do Projeto</label>
                            <input type="text" id="titulo" name="titulo" value="{{ old('titulo') }}"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white @error('titulo') border-red-500 @enderror"
                                   placeholder="Ex: Desenvolvimento de um E-commerce em Laravel" required>
                            @error('titulo')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>


                        <div>
                            <label for="descricao" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-100">Descrição Completa</label>
                            <textarea id="descricao" name="descricao" rows="5"
                                      class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white @error('descricao') border-red-500 @enderror"
                                      placeholder="Descreva os requisitos, funcionalidades e objetivos do projeto..." required>{{ old('descricao') }}</textarea>
                            @error('descricao')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                            @enderror
                        </div>


                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <div>
                                <label for="orcamento" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-100">Orçamento (R$)</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">R$</span>
                                    </div>
                                    <input type="number" step="0.01" id="orcamento" name="orcamento" value="{{ old('orcamento') }}"
                                           class="pl-10 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white @error('orcamento') border-red-500 @enderror"
                                           placeholder="1500.00" required>
                                </div>
                                @error('orcamento')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                            </div>


                            <div>
                                <label for="prazo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-100">Prazo Final</p>
                                <input type="date" id="prazo" name="prazo" value="{{ old('prazo') }}"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white @error('prazo') border-red-500 @enderror"
                                       required>
                                @error('prazo')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>


                        <div>
                             <label for="categorias" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-100">Categorias do Projeto</label>
                             <select name="categorias[]" id="categorias" multiple
                                     class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white @error('categorias') border-red-500 @enderror">
                                 @foreach($categorias as $categoria)
                                     <option value="{{ $categoria->id }}" @selected(in_array($categoria->id, old('categorias', [])))>
                                         {{ $categoria->nome }}
                                     </option>
                                 @endforeach
                             </select>
                             @error('categorias')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                             @enderror
                             <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Segure 'Ctrl' (ou 'Cmd' no Mac) para selecionar várias categorias.</p>
                        </div>


                        <div class="flex items-center justify-end pt-4 border-t border-gray-200 dark:border-gray-700 space-x-4">
                            <a href="{{ route('projetos.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-25 transition ease-in-out duration-150">
                                Cancelar
                            </a>
                            <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 active:bg-primary-700 disabled:opacity-25 transition ease-in-out duration-150">
                                Publicar Projeto
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
