<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Painel de Controle') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- ADMIN DASHBOARD --}}
            @if (Auth::user()->isAdministrador())
                <div class="space-y-8">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Painel do Administrador</h1>
                        <p class="mt-1 text-lg text-gray-600 dark:text-gray-400">Gerencie todos os aspectos da plataforma.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <a href="{{ route('admin.users.index') }}" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all flex items-start gap-4">
                            <div class="w-12 h-12 flex items-center justify-center bg-red-100 dark:bg-red-900/50 rounded-lg flex-shrink-0">
                                <svg class="w-6 h-6 text-red-600 dark:text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-2.305c.395-.44-.09-.964-.538-.964h-3.234M15 19.128v-6.042A8.967 8.967 0 0015 3.75c-2.823 0-5.434 1.343-7.16 3.37M15 19.128a2.25 2.25 0 01-2.25-2.25V6.75" /></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-lg text-gray-900 dark:text-gray-100">Gerenciar Usuários</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Visualize e edite todos os usuários.</p>
                            </div>
                        </a>
                        <a href="{{ route('admin.categorias.index') }}" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all flex items-start gap-4">
                            <div class="w-12 h-12 flex items-center justify-center bg-yellow-100 dark:bg-yellow-900/50 rounded-lg flex-shrink-0">
                                <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" /><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" /></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-lg text-gray-900 dark:text-gray-100">Gerenciar Categorias</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Crie e edite as categorias de projetos.</p>
                            </div>
                        </a>
                        <a href="{{ route('projetos.index') }}" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all flex items-start gap-4">
                            <div class="w-12 h-12 flex items-center justify-center bg-green-100 dark:bg-green-900/50 rounded-lg flex-shrink-0">
                                <svg class="w-6 h-6 text-green-600 dark:text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75l7.5-7.5 7.5 7.5m-15 6l7.5-7.5 7.5 7.5" /></svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-lg text-gray-900 dark:text-gray-100">Ver Todos os Projetos</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Monitore todos os projetos da plataforma.</p>
                            </div>
                        </a>
                    </div>
                </div>

            {{-- CLIENTE DASHBOARD --}}
            @elseif (Auth::user()->isCliente())
                @php
                    $projetosAbertosCliente = Auth::user()->projetosPublicados()->where('status', 'aberto')->count();
                    $projetosEmAndamentoCliente = Auth::user()->projetosPublicados()->where('status', 'em_andamento')->count();
                    $projetosAguardandoAprovacaoCliente = Auth::user()->projetosPublicados()->where('status', 'aguardando_aprovacao')->count();
                    $projetosConcluidosCliente = Auth::user()->projetosPublicados()->where('status', 'concluido')->count();
                @endphp
                <div class="space-y-8">
                     <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Painel do Cliente</h1>
                        <p class="mt-1 text-lg text-gray-600 dark:text-gray-400">Gerencie seus projetos e contratações.</p>
                    </div>

                    {{-- Stats --}}
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                            <p class="text-4xl font-extrabold text-blue-500">{{ $projetosAbertosCliente }}</p>
                            <p class="mt-1 text-sm font-medium text-gray-500 dark:text-gray-400">Abertos</p>
                        </div>
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                            <p class="text-4xl font-extrabold text-yellow-500">{{ $projetosEmAndamentoCliente }}</p>
                            <p class="mt-1 text-sm font-medium text-gray-500 dark:text-gray-400">Em Andamento</p>
                        </div>
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                            <p class="text-4xl font-extrabold text-indigo-500">{{ $projetosAguardandoAprovacaoCliente }}</p>
                            <p class="mt-1 text-sm font-medium text-gray-500 dark:text-gray-400">Aguardando Aprovação</p>
                        </div>
                         <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                            <p class="text-4xl font-extrabold text-green-500">{{ $projetosConcluidosCliente }}</p>
                            <p class="mt-1 text-sm font-medium text-gray-500 dark:text-gray-400">Concluídos</p>
                        </div>
                    </div>

                    {{-- Ações --}}
                     <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                         <a href="{{ route('projetos.create') }}" class="bg-primary-600 text-white p-6 rounded-lg shadow-lg hover:bg-primary-700 transition-all flex items-center gap-4">
                            <svg class="w-10 h-10" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <div>
                                <h3 class="font-bold text-lg">Publicar Novo Projeto</h3>
                                <p class="text-sm opacity-90">Descreva sua ideia e encontre o freelancer ideal.</p>
                            </div>
                        </a>
                         <a href="{{ route('projetos.index') }}" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all flex items-center gap-4">
                            <svg class="w-10 h-10 text-primary-600 dark:text-primary-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75l7.5-7.5 7.5 7.5m-15 6l7.5-7.5 7.5 7.5" /></svg>
                            <div>
                                <h3 class="font-bold text-lg text-gray-900 dark:text-gray-100">Meus Projetos Publicados</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Acompanhe o status e as propostas.</p>
                            </div>
                        </a>
                    </div>
                </div>

            {{-- FREELANCER DASHBOARD --}}
            @elseif (Auth::user()->isFreelancer())
                {{-- Reutilizando o mesmo design do freelancer/dashboard.blade.php para consistência --}}
                <div class="space-y-8">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Painel do Freelancer</h1>
                        <p class="mt-1 text-lg text-gray-600 dark:text-gray-400">Seu resumo de atividades e oportunidades.</p>
                    </div>

                    @php
                        $projetosEmAndamentoFreelancer = Auth::user()->projetosAceitos()->where('status', 'em_andamento')->count();
                        $candidaturasPendentes = Auth::user()->candidaturas()->where('status', 'pendente')->count();
                    @endphp

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                         <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6 flex items-center gap-6">
                            <div class="w-12 h-12 flex items-center justify-center bg-indigo-100 dark:bg-indigo-900/50 rounded-lg"><svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" /></svg></div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Projetos Ativos</p>
                                <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $projetosEmAndamentoFreelancer }}</p>
                            </div>
                        </div>
                        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6 flex items-center gap-6">
                            <div class="w-12 h-12 flex items-center justify-center bg-blue-100 dark:bg-blue-900/50 rounded-lg"><svg class="w-6 h-6 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" /></svg></div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Candidaturas Pendentes</p>
                                <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $candidaturasPendentes }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <a href="{{ route('projetos.index') }}" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all flex items-center gap-4">
                           <div class="w-12 h-12 flex items-center justify-center bg-primary-100 dark:bg-primary-900/50 rounded-full flex-shrink-0"><svg class="w-6 h-6 text-primary-600 dark:text-primary-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 15.75l-2.489-2.489m0 0a3.375 3.375 0 10-4.773-4.773 3.375 3.375 0 004.774 4.774zM21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></div>
                           <div>
                                <h3 class="font-bold text-lg text-gray-900 dark:text-gray-100">Ver Projetos Disponíveis</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Encontre novas oportunidades de trabalho.</p>
                           </div>
                        </a>
                         <a href="{{ route('freelancer.profile.edit') }}" class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all flex items-center gap-4">
                           <div class="w-12 h-12 flex items-center justify-center bg-green-100 dark:bg-green-900/50 rounded-full flex-shrink-0"><svg class="w-6 h-6 text-green-600 dark:text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" /></svg></div>
                           <div>
                                <h3 class="font-bold text-lg text-gray-900 dark:text-gray-100">Editar Perfil Público</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Mantenha suas informações atualizadas.</p>
                           </div>
                        </a>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
