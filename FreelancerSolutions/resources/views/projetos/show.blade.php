<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detalhes do Projeto') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Alertas de Sessão --}}
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

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- COLUNA PRINCIPAL (ESQUERDA) --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- CARD DE DETALHES DO PROJETO --}}
                    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
                        <div class="p-6 sm:p-8">
                            @php
                                // Lógica para cores do status
                                $statusClasses = match($projeto->status) {
                                    'aberto' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
                                    'em_andamento' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                    'concluido' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                    'cancelado' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                    default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                };
                            @endphp

                            <div class="flex flex-col sm:flex-row justify-between sm:items-start gap-4">
                                <h3 class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $projeto->titulo }}</h3>
                                <span class="text-sm font-medium px-3 py-1 rounded-full whitespace-nowrap self-start {{ $statusClasses }}">
                                    {{ str_replace('_', ' ', $projeto->status) }}
                                </span>
                            </div>

                            <p class="mt-4 text-gray-700 dark:text-gray-300 leading-relaxed">{{ $projeto->descricao }}</p>

                            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                                <div class="flex items-center gap-3">
                                    <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center bg-gray-100 dark:bg-gray-700 rounded-lg"><span class="text-xl">💰</span></div>
                                    <div>
                                        <p class="text-gray-500 dark:text-gray-400">Orçamento</p>
                                        <p class="font-semibold text-gray-900 dark:text-white">R$ {{ number_format($projeto->orcamento, 2, ',', '.') }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                     <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center bg-gray-100 dark:bg-gray-700 rounded-lg"><span class="text-xl">🗓️</span></div>
                                    <div>
                                        <p class="text-gray-500 dark:text-gray-400">Prazo Final</p>
                                        <p class="font-semibold text-gray-900 dark:text-white">{{ $projeto->prazo->format('d/m/Y') }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center bg-gray-100 dark:bg-gray-700 rounded-lg"><span class="text-xl">👤</span></div>
                                    <div>
                                        <p class="text-gray-500 dark:text-gray-400">Cliente</p>
                                        <p class="font-semibold text-gray-900 dark:text-white">{{ $projeto->cliente->name }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                     <div class="flex-shrink-0 w-10 h-10 flex items-center justify-center bg-gray-100 dark:bg-gray-700 rounded-lg"><span class="text-xl">🛠️</span></div>
                                    <div>
                                        <p class="text-gray-500 dark:text-gray-400">Freelancer</p>
                                        <p class="font-semibold text-gray-900 dark:text-white">
                                            @if ($projeto->freelancerAceito)
                                                <a href="{{ route('freelancers.show', $projeto->freelancerAceito->freelancer) }}" class="text-primary-600 hover:underline">
                                                    {{ $projeto->freelancerAceito->freelancer->name }}
                                                </a>
                                            @else
                                                Nenhum selecionado
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Categorias</p>
                                <div class="mt-2 flex flex-wrap gap-2">
                                     @forelse ($projeto->categorias as $categoria)
                                        <span class="inline-block bg-primary-100 text-primary-800 text-xs font-medium px-2.5 py-0.5 rounded-full dark:bg-primary-900 dark:text-primary-300">{{ $categoria->nome }}</span>
                                    @empty
                                        <p class="text-sm text-gray-500">Nenhuma categoria.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        {{-- SEÇÃO DE BOTÕES DE AÇÃO --}}
                        <div class="p-6 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-200 dark:border-gray-700">
                             <div class="flex flex-wrap items-center gap-3">
                                <a href="{{ route('projetos.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700">Voltar</a>

                                {{-- Ações para Cliente e Freelancer do Projeto --}}
                                {{-- Primeiro, verificamos se já existe um freelancer aceito para habilitar o chat --}}
                                @if ($projeto->freelancerAceito)

                                    {{-- Agora, verificamos se o usuário logado é uma das duas pessoas que podem conversar --}}
                                    @if (Auth::id() === $projeto->cliente_id || Auth::id() === $projeto->freelancerAceito->freelancer_id)

                                        {{-- Bloco PHP para determinar quem é o "outro" na conversa --}}
                                        @php
                                            $destinatarioDoChat = (Auth::id() === $projeto->cliente_id)
                                                                ? $projeto->freelancerAceito->freelancer
                                                                : $projeto->cliente;
                                        @endphp

                                        {{-- Link corrigido, agora passando o projeto E o destinatário para a rota --}}
                                        <a href="{{ route('mensagens.index', ['projeto' => $projeto, 'destinatario' => $destinatarioDoChat]) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500">
                                            <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.76c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.076-4.076a1.526 1.526 0 011.037-.443 48.282 48.282 0 005.68-.494c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" /></svg>
                                            Mensagens
                                        </a>
                                    @endif
                                @endif

                                {{-- Ações do Cliente --}}
                                @if(Auth::user()->id === $projeto->cliente_id)
                                    @can('update', $projeto)
                                        <a href="{{ route('projetos.edit', $projeto) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-400">Editar</a>
                                    @endcan

                                    @if ($projeto->status === 'aguardando_aprovacao')
                                        <form action="{{ route('projetos.update', $projeto) }}" method="POST" class="inline"> @csrf @method('PUT') <input type="hidden" name="status" value="em_andamento"><button type="submit" onclick="return confirm('Deseja solicitar uma revisão?')" class="inline-flex items-center px-4 py-2 bg-orange-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-400">Solicitar Revisão</button></form>
                                        <form action="{{ route('projetos.update', $projeto) }}" method="POST" class="inline"> @csrf @method('PUT') <input type="hidden" name="status" value="concluido"><button type="submit" onclick="return confirm('Finalizar e aprovar este projeto?')" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500">Finalizar Projeto</button></form>
                                    @endif
                                @endif

                                {{-- Ações do Freelancer --}}
                                @if(Auth::check() && Auth::user()->isFreelancer())
                                     @if ($projeto->status === 'aberto' && !$projeto->candidaturas->where('freelancer_id', Auth::id())->first())
                                        {{-- Modal de Candidatura com Alpine.js --}}
                                        <div x-data="{ open: false }">
                                            <button @click="open = true" type="button" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500">Candidatar-se</button>

                                            <div x-show="open" @click.away="open = false" x-transition class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
                                                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-lg">
                                                    <form action="{{ route('candidaturas.store') }}" method="POST" class="space-y-4">
                                                        @csrf
                                                        <div class="p-6 border-b dark:border-gray-700">
                                                            <h5 class="text-xl font-bold">Candidatar-se a: {{ $projeto->titulo }}</h5>
                                                        </div>
                                                        <div class="p-6 space-y-4">
                                                            <input type="hidden" name="projeto_id" value="{{ $projeto->id }}">
                                                            <div>
                                                                <label for="proposta" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-100">Sua Proposta</label>
                                                                <textarea class="w-full bg-gray-50 border-gray-300 dark:bg-gray-700 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500" id="proposta" name="proposta" rows="4" required></textarea>
                                                            </div>
                                                            <div>
                                                                <label for="proposta_valor" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-100">Valor da Proposta (R$)</label>
                                                                <input type="number" step="0.01" min="0" class="w-full bg-gray-50 border-gray-300 dark:bg-gray-700 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500" id="proposta_valor" name="proposta_valor" required>
                                                            </div>
                                                            <div>
                                                                <label for="proposta_prazo" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-100">Seu Prazo (dias)</label>
                                                                <input type="number" min="1" class="w-full bg-gray-50 border-gray-300 dark:bg-gray-700 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500" id="proposta_prazo" name="proposta_prazo" required>
                                                            </div>
                                                        </div>
                                                        <div class="p-4 bg-gray-50 dark:bg-gray-900/50 flex justify-end gap-3">
                                                            <button type="button" @click="open = false" class="px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs uppercase hover:bg-gray-50">Fechar</button>
                                                            <button type="submit" class="px-4 py-2 bg-primary-600 border rounded-md font-semibold text-xs text-white uppercase hover:bg-primary-500">Enviar</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @elseif ($projeto->freelancerAceito && $projeto->freelancerAceito->freelancer_id === Auth::id() && $projeto->status === 'em_andamento')
                                        <form action="{{ route('projetos.update', $projeto) }}" method="POST" class="inline"> @csrf @method('PUT') <input type="hidden" name="status" value="aguardando_aprovacao"><button type="submit" onclick="return confirm('Marcar como concluído?')" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500">Marcar como Concluído</button></form>
                                    @endif
                                @endif
                             </div>
                        </div>
                    </div>

                    {{-- CARD DE AVALIAÇÕES --}}
                     <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                           <h4 class="text-xl font-bold text-gray-900 dark:text-gray-100">Avaliações do Projeto</h4>
                        </div>
                        <div class="p-6 space-y-4">
                            @forelse ($projeto->avaliacoes as $avaliacao)
                                <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                                    <div class="flex justify-between items-center">
                                        <p class="font-semibold text-gray-800 dark:text-gray-200">{{ $avaliacao->avaliador->name }} <span class="text-xs font-normal text-gray-500">({{ $avaliacao->tipo_avaliacao === 'cliente_para_freelancer' ? 'Cliente' : 'Freelancer' }})</span></p>
                                        <div class="flex items-center text-yellow-500">
                                            @for ($i = 0; $i < $avaliacao->nota; $i++) <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg> @endfor
                                            @for ($i = $avaliacao->nota; $i < 5; $i++) <svg class="w-4 h-4 fill-current text-gray-300 dark:text-gray-600" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg> @endfor
                                        </div>
                                    </div>
                                    <p class="mt-2 text-gray-600 dark:text-gray-400">{{ $avaliacao->comentario }}</p>
                                    <p class="mt-1 text-xs text-gray-500">{{ $avaliacao->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500 dark:text-gray-400">Nenhuma avaliação para este projeto ainda.</p>
                            @endforelse
                        </div>
                        @if ($projeto->status === 'concluido' && Auth::check())
                            <div class="p-6 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-200 dark:border-gray-700">
                                @php $podeAvaliar = false; @endphp
                                @if (Auth::user()->id === $projeto->cliente_id && !$projeto->avaliacoes->where('avaliador_id', Auth::id())->first()) @php $podeAvaliar = true; @endphp
                                @elseif ($projeto->freelancerAceito && Auth::user()->id === $projeto->freelancerAceito->freelancer_id && !$projeto->avaliacoes->where('avaliador_id', Auth::id())->first()) @php $podeAvaliar = true; @endphp
                                @endif
                                @if($podeAvaliar)
                                    <a href="{{ route('avaliacoes.create', $projeto) }}" class="inline-flex items-center px-4 py-2 bg-primary-600 border rounded-md font-semibold text-xs text-white uppercase hover:bg-primary-500">Deixar uma Avaliação</a>
                                @else
                                    <p class="text-sm text-gray-500">Você já avaliou este projeto.</p>
                                @endif
                            </div>
                        @endif
                       </div>

                </div>

                {{-- COLUNA LATERAL (DIREITA) --}}
                <div class="lg:col-span-1 space-y-6">
                    {{-- CARD DE CANDIDATURAS (Visível para o cliente) --}}
                    @if(Auth::check() && Auth::user()->id === $projeto->cliente_id)
                        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg">
                            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                                <h4 class="text-xl font-bold text-gray-900 dark:text-gray-100">Candidaturas</h4>
                            </div>
                            <div class="p-6 space-y-4">
                                @forelse ($projeto->candidaturas as $candidatura)
                                    <div class="p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                                        <div class="flex justify-between items-center">
                                            <a href="{{ route('freelancers.show', $candidatura->freelancer) }}" class="font-semibold text-primary-600 hover:underline">{{ $candidatura->freelancer->name }}</a>
                                            <span class="text-xs font-medium px-2.5 py-0.5 rounded-full {{ $candidatura->status == 'aceita' ? 'bg-green-100 text-green-800' : ($candidatura->status == 'recusada' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800') }}">{{ ucfirst($candidatura->status) }}</span>
                                        </div>
                                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400 italic">"{{ $candidatura->proposta }}"</p>
                                        @if($candidatura->status === 'pendente' && !$projeto->freelancerAceito)
                                            <div class="mt-3 flex gap-2">
                                                <form action="{{ route('candidaturas.aceitar', $candidatura) }}" method="POST">@csrf <button type="submit" onclick="return confirm('Aceitar esta candidatura?')" class="px-3 py-1 text-xs font-medium text-white bg-green-600 rounded-md hover:bg-green-500">Aceitar</button></form>
                                                <form action="{{ route('candidaturas.recusar', $candidatura) }}" method="POST">@csrf <button type="submit" onclick="return confirm('Recusar esta candidatura?')" class="px-3 py-1 text-xs font-medium text-white bg-red-600 rounded-md hover:bg-red-500">Recusar</button></form>
                                            </div>
                                        @endif
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Nenhuma candidatura recebida.</p>
                                @endforelse
                            </div>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
