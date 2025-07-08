<x-app-layout>
    {{-- Slot do Cabeçalho da Página --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Chat do Projeto: ') . $projeto->titulo }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg flex flex-col h-[75vh]">

                {{-- CABEÇALHO DO CHAT --}}
                <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center flex-shrink-0">
                    <div class="flex items-center gap-4">
                        @if ($outroParticipante)
                            @php
                                // Lógica do Avatar
                                $initials = strtoupper(substr($outroParticipante->name, 0, 1) . (strpos($outroParticipante->name, ' ') ? substr(strrchr($outroParticipante->name, ' '), 1, 1) : ''));
                                $colors = ['bg-blue-200 text-blue-800', 'bg-green-200 text-green-800', 'bg-yellow-200 text-yellow-800', 'bg-purple-200 text-purple-800', 'bg-red-200 text-red-800', 'bg-indigo-200 text-indigo-800'];
                                $colorClass = $colors[crc32($outroParticipante->id) % count($colors)];
                            @endphp
                            <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold {{ $colorClass }}">
                                {{ $initials }}
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ $outroParticipante->name }}
                            </h3>
                        @else
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                Chat do Projeto
                            </h3>
                        @endif
                    </div>
                    <a href="{{ route('projetos.show', $projeto) }}" class="text-sm text-primary-600 dark:text-primary-400 hover:underline">
                        Voltar
                    </a>
                </div>

                {{-- ÁREA DAS MENSAGENS --}}
                <div id="chat-box" class="flex-grow p-6 space-y-6 overflow-y-auto">
                    @forelse ($mensagens as $mensagem)
                        <div class="flex items-end gap-3 {{ $mensagem->remetente_id === Auth::id() ? 'justify-end' : '' }}">

                            {{-- Avatar para mensagens recebidas --}}
                            @if ($mensagem->remetente_id !== Auth::id())
                                @php
                                    $remetente = $mensagem->remetente;
                                    $initials = strtoupper(substr($remetente->name, 0, 1) . (strpos($remetente->name, ' ') ? substr(strrchr($remetente->name, ' '), 1, 1) : ''));
                                    $colorClass = $colors[crc32($remetente->id) % count($colors)];
                                @endphp
                                <div class="w-8 h-8 rounded-full flex-shrink-0 flex items-center justify-center font-bold text-xs {{ $colorClass }}">
                                    {{ $initials }}
                                </div>
                            @endif

                            {{-- Bubble da Mensagem --}}
                            <div class="max-w-xs md:max-w-md p-3 rounded-2xl {{ $mensagem->remetente_id === Auth::id() ? 'bg-primary-600 text-white rounded-br-lg' : 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-bl-lg' }}">
                                <p class="text-sm break-words">{{ $mensagem->conteudo }}</p>
                                <p class="text-xs mt-1 opacity-75 text-right">{{ $mensagem->created_at->format('H:i') }}</p>
                            </div>

                        </div>
                    @empty
                        <p class="text-center text-gray-500 pt-10">Nenhuma mensagem ainda. Comece a conversa!</p>
                    @endforelse
                </div>

                {{-- RODAPÉ COM FORMULÁRIO DE ENVIO --}}
                <div class="p-4 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-200 dark:border-gray-700 flex-shrink-0">
                    @if ($outroParticipante)
                        <form action="{{ route('mensagens.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="projeto_id" value="{{ $projeto->id }}">
                            <input type="hidden" name="destinatario_id" value="{{ $outroParticipante->id }}">

                            <div class="flex items-center gap-3">
                                <textarea name="conteudo" rows="1" class="flex-grow bg-gray-100 dark:bg-gray-700 border-gray-200 dark:border-gray-600 rounded-full p-3 px-5 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200" placeholder="Escreva sua mensagem..." required></textarea>
                                <button type="submit" class="inline-flex items-center justify-center h-12 w-12 rounded-full bg-primary-600 text-white hover:bg-primary-500 transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 flex-shrink-0">
                                    <svg class="w-6 h-6 transform rotate-90" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                                    </svg>
                                    <span class="sr-only">Enviar</span>
                                </button>
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('conteudo')" />
                        </form>
                    @else
                        <div class="text-center text-sm text-yellow-800 dark:text-yellow-300">
                           Para iniciar o chat, o projeto precisa ter um freelancer selecionado.
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

    <script>
        // Script para rolar a caixa de chat para a mensagem mais recente
        document.addEventListener('DOMContentLoaded', function() {
            const chatBox = document.getElementById('chat-box');
            chatBox.scrollTop = chatBox.scrollHeight;

            // Script para auto-ajustar a altura do textarea
            const textarea = document.querySelector('textarea[name="conteudo"]');
            if (textarea) {
                textarea.addEventListener('input', () => {
                    textarea.style.height = 'auto';
                    textarea.style.height = (textarea.scrollHeight) + 'px';
                });
            }
        });
    </script>
</x-app-layout>
