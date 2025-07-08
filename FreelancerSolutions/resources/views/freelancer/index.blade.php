<x-app-layout>
    {{-- Slot do Cabeçalho da Página --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Freelancers') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Título Principal --}}
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Encontre os Melhores Talentos</h1>
                 <p class="text-lg text-gray-600 dark:text-gray-400 mt-1">
                    Navegue pela nossa lista de freelancers qualificados.
                </p>
            </div>

            @if ($freelancers->isEmpty())
                {{-- Mensagem para quando não houver freelancers --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-8 text-center text-gray-500 dark:text-gray-400">
                        Nenhum freelancer encontrado.
                    </div>
                </div>
            @else
                {{-- Grid de Cards de Freelancers --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach ($freelancers as $freelancer)
                        @php
                            // Lógica para gerar um avatar com as iniciais e uma cor de fundo
                            $initials = strtoupper(substr($freelancer->name, 0, 1) . (strpos($freelancer->name, ' ') ? substr(strrchr($freelancer->name, ' '), 1, 1) : ''));
                            $colors = ['bg-blue-200 text-blue-800', 'bg-green-200 text-green-800', 'bg-yellow-200 text-yellow-800', 'bg-purple-200 text-purple-800', 'bg-red-200 text-red-800', 'bg-indigo-200 text-indigo-800'];
                            $colorClass = $colors[crc32($freelancer->id) % count($colors)];
                        @endphp
                        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden text-center transition-transform duration-300 hover:-translate-y-2 flex flex-col">
                            <div class="p-6 flex-grow">
                                {{-- Avatar com Iniciais --}}
                                <div class="w-24 h-24 rounded-full mx-auto flex items-center justify-center {{ $colorClass }}">
                                    <span class="text-4xl font-bold">{{ $initials }}</span>
                                </div>

                                {{-- Nome e Email --}}
                                <h3 class="mt-4 text-xl font-bold text-gray-900 dark:text-gray-100 truncate" title="{{ $freelancer->name }}">{{ $freelancer->name }}</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 truncate" title="{{ $freelancer->email }}">{{ $freelancer->email }}</p>
                            </div>

                            {{-- Rodapé com Link de Ação --}}
                            <div class="border-t border-gray-200 dark:border-gray-700">
                                <a href="{{ route('freelancers.show', $freelancer->id) }}" class="block w-full py-3 px-4 text-sm font-semibold text-primary-600 dark:text-primary-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                    Ver Perfil
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Paginação --}}
                @if ($freelancers->hasPages())
                    <div class="mt-8">
                        {{ $freelancers->links() }}
                    </div>
                @endif
            @endif

        </div>
    </div>
</x-app-layout>
