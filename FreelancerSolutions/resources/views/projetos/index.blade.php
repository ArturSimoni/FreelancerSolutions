<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Projetos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">


            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                    {{ Auth::user()->isCliente() ? 'Meus Projetos' : (Auth::user()->isFreelancer() ? 'Projetos Disponíveis' : 'Todos os Projetos') }}
                </h3>

                @if (Auth::user()->isCliente())
                    <a href="{{ route('projetos.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 active:bg-primary-700 disabled:opacity-25 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2 -ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Publicar Novo Projeto
                    </a>
                @endif
            </div>


            @if (session('success'))
                <div class="mb-4 rounded-lg bg-green-100 dark:bg-green-900 p-4 text-sm text-green-700 dark:text-green-200" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                 <div class="mb-4 rounded-lg bg-red-100 dark:bg-red-900 p-4 text-sm text-red-700 dark:text-red-200" role="alert">
                    {{ session('error') }}
                </div>
            @endif


            @if ($projetos->isEmpty())
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                        Nenhum projeto encontrado.
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($projetos as $projeto)
                        @php

                            $statusClasses = match($projeto->status) {
                                'aberto' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                                'em_andamento' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                'concluido' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                'cancelado' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                            };
                        @endphp

                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transition-transform duration-300 hover:scale-[1.02] hover:shadow-xl flex flex-col">

                            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                                <div class="flex justify-between items-start gap-2">
                                    <h4 class="font-bold text-lg text-gray-900 dark:text-gray-100 break-words">
                                        {{ $projeto->titulo }}
                                    </h4>
                                    <span class="text-xs font-medium px-2.5 py-0.5 rounded-full whitespace-nowrap {{ $statusClasses }}">
                                        {{ str_replace('_', ' ', $projeto->status) }}
                                    </span>
                                </div>
                            </div>


                            <div class="p-4 space-y-4 flex-grow">
                                <div class="text-sm text-gray-600 dark:text-gray-400">
                                    <span class="font-semibold text-gray-800 dark:text-gray-200">Cliente:</span> {{ $projeto->cliente->name }}
                                </div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">
                                    <span class="font-semibold text-gray-800 dark:text-gray-200">Orçamento:</span> R$ {{ number_format($projeto->orcamento, 2, ',', '.') }}
                                </div>
                                <div class="text-sm text-gray-600 dark:text-gray-400">
                                    <span class="font-semibold text-gray-800 dark:text-gray-200">Prazo:</span> {{ $projeto->prazo->format('d/m/Y') }}
                                </div>
                            </div>

                            <div class="p-4 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-200 dark:border-gray-700">
                                <div class="flex justify-end items-center space-x-3">
                                    <a href="{{ route('projetos.show', $projeto) }}" class="text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-200 font-medium text-sm">Ver Detalhes</a>

                                    @can('update', $projeto)
                                        <a href="{{ route('projetos.edit', $projeto) }}" class="text-yellow-600 hover:text-yellow-800 font-medium text-sm">Editar</a>
                                    @endcan

                                    @can('delete', $projeto)
                                        <form action="{{ route('projetos.destroy', $projeto) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 font-medium text-sm" onclick="return confirm('Tem certeza que deseja excluir este projeto?')">Excluir</button>
                                        </form>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
