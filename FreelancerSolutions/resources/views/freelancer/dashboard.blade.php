<x-app-layout>
    {{-- Slot do Cabeçalho --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Painel do Freelancer') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Mensagem de Boas-Vindas --}}
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                    Bem-vindo(a) de volta, {{ Auth::user()->name }}!
                </h1>
                <p class="text-lg text-gray-600 dark:text-gray-400">
                    Aqui está um resumo da sua atividade na plataforma.
                </p>
            </div>

            {{-- Alerta para Completar o Perfil (Exemplo Condicional) --}}
            {{-- Você pode adicionar uma lógica como @if (!Auth::user()->profile_is_complete) --}}
            <div class="mb-8 p-4 bg-blue-100 dark:bg-blue-900/50 border-l-4 border-blue-500 text-blue-800 dark:text-blue-200 rounded-r-lg" role="alert">
                <div class="flex items-center">
                    <svg class="w-6 h-6 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" /></svg>
                    <div>
                        <p class="font-bold">Seu perfil está quase pronto!</p>
                        <p class="text-sm">Um perfil completo atrai mais clientes. Adicione suas habilidades e portfólio.</p>
                    </div>
                    <a href="#" {{-- Substitua pelo link de editar perfil --}} class="ml-auto px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                        Completar Perfil
                    </a>
                </div>
            </div>
            {{-- @endif --}}


            {{-- Cards de Estatísticas --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                {{-- Card 1: Projetos Ativos --}}
                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6 flex items-center gap-6">
                    <div class="w-12 h-12 flex items-center justify-center bg-indigo-100 dark:bg-indigo-900/50 rounded-lg">
                        <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" /></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Projetos Ativos</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">3</p> {{-- Substituir por variável --}}
                    </div>
                </div>

                {{-- Card 2: Propostas Enviadas --}}
                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6 flex items-center gap-6">
                    <div class="w-12 h-12 flex items-center justify-center bg-blue-100 dark:bg-blue-900/50 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" /></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Propostas Enviadas</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">12</p> {{-- Substituir por variável --}}
                    </div>
                </div>

                {{-- Card 3: Avaliação Média --}}
                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6 flex items-center gap-6">
                    <div class="w-12 h-12 flex items-center justify-center bg-yellow-100 dark:bg-yellow-900/50 rounded-lg">
                       <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" /></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Sua Avaliação</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">4.9</p> {{-- Substituir por variável --}}
                    </div>
                </div>

                {{-- Card 4: Ganhos Totais --}}
                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6 flex items-center gap-6">
                    <div class="w-12 h-12 flex items-center justify-center bg-green-100 dark:bg-green-900/50 rounded-lg">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" /></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Ganhos Totais</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">R$ 1.234</p> {{-- Substituir por variável --}}
                    </div>
                </div>
            </div>

            {{-- Seção de Ações Rápidas --}}
            <div class="mt-10">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">Ações Rápidas</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <a href="{{ route('projetos.index') }}" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all flex flex-col items-center text-center">
                        <div class="w-16 h-16 flex items-center justify-center bg-primary-100 dark:bg-primary-900/50 rounded-full mb-4">
                           <svg class="w-8 h-8 text-primary-600 dark:text-primary-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 15.75l-2.489-2.489m0 0a3.375 3.375 0 10-4.773-4.773 3.375 3.375 0 004.774 4.774zM21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <h3 class="font-bold text-lg text-gray-900 dark:text-gray-100">Ver Projetos</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Encontre novas oportunidades.</p>
                    </a>
                    {{-- Adicione outros links de ação aqui, como 'Minhas Propostas' --}}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
