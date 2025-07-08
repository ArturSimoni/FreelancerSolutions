<x-app-layout>
    {{-- Slot do Cabeçalho --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Avaliar Serviço') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg overflow-hidden">
                <form action="{{ route('avaliacoes.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="projeto_id" value="{{ $projeto->id }}">
                    <input type="hidden" name="avaliado_id" value="{{ $avaliado->id }}">
                    <input type="hidden" name="tipo_avaliacao" value="{{ $tipo_avaliacao }}">

                    {{-- Cabeçalho do Card de Avaliação --}}
                    <div class="p-6 sm:p-8 border-b border-gray-200 dark:border-gray-700 text-center">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                            Avalie sua experiência
                        </h3>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            Projeto: <span class="font-semibold">{{ $projeto->titulo }}</span>
                        </p>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            @if ($tipo_avaliacao === 'cliente_para_freelancer')
                                Você está avaliando o freelancer: <span class="font-semibold">{{ $avaliado->name }}</span>
                            @else
                                Você está avaliando o cliente: <span class="font-semibold">{{ $avaliado->name }}</span>
                            @endif
                        </p>
                    </div>

                    {{-- Corpo do Formulário --}}
                    <div class="p-6 sm:p-8 space-y-6">

                        {{-- Componente de Avaliação por Estrelas --}}
                        <div x-data="{ rating: {{ old('nota', 0) }}, hoverRating: 0 }" class="space-y-2">
                            <x-input-label for="nota" :value="__('Sua Nota (de 1 a 5)')" class="text-center" />

                            {{-- Inputs de Rádio escondidos para o formulário --}}
                            <div class="sr-only">
                                @for ($i = 1; $i <= 5; $i++)
                                    <input type="radio" name="nota" id="nota{{$i}}" value="{{$i}}" x-model.number="rating" required>
                                @endfor
                            </div>

                            {{-- Estrelas visíveis e interativas --}}
                            <div class="flex justify-center items-center space-x-1" @mouseleave="hoverRating = 0">
                                @for ($i = 1; $i <= 5; $i++)
                                    <label for="nota{{$i}}"
                                           @mouseenter="hoverRating = {{ $i }}"
                                           class="cursor-pointer text-gray-300 dark:text-gray-600 transition-colors duration-150"
                                           :class="{
                                                '!text-yellow-400': hoverRating >= {{ $i }},
                                                'text-yellow-400': rating >= {{ $i }}
                                           }">
                                        <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
                                    </label>
                                @endfor
                            </div>
                            <x-input-error class="mt-2 text-center" :messages="$errors->get('nota')" />
                        </div>

                        <div>
                            <x-input-label for="comentario" :value="__('Deixe um comentário (Opcional)')" />
                            <textarea id="comentario" name="comentario" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full" rows="4" placeholder="Descreva sua experiência, elogie um bom trabalho ou dê um feedback construtivo...">{{ old('comentario') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('comentario')" />
                        </div>
                    </div>

                    {{-- Rodapé com Botões de Ação --}}
                    <div class="p-6 bg-gray-50 dark:bg-gray-900/50 flex items-center justify-end gap-4">
                        <a href="{{ route('projetos.show', $projeto) }}" class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:underline">
                            Cancelar
                        </a>
                        <x-primary-button>
                            {{ __('Enviar Avaliação') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
