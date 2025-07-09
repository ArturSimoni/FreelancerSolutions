<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
            {{ __('Painel do Administrador') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-2xl font-bold border-b border-gray-200 dark:border-gray-700 pb-4">
                        {{ __("Bem-vindo, Administrador!") }}
                    </h3>

                    <p class="mt-4 text-gray-600 dark:text-gray-400">
                        Use os cartões abaixo para navegar pelas seções de gerenciamento do sistema.
                    </p>
                </div>
            </div>

            {{-- Seção de Cards de Gerenciamento --}}
            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Card de Gerenciar Usuários --}}
                <a href="{{ route('admin.users.index') }}" class="block p-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372m-4.001-4.264a9.369 9.369 0 01-4.263-4.001M12 20.25a9.369 9.369 0 01-4.263-4.001m-1.366-3.212a9.38 9.38 0 01-.372-2.625m4.001 4.264a9.369 9.369 0 004.263 4.001M12 3.75a9.369 9.369 0 00-4.263 4.001m1.366 3.212a9.38 9.38 0 00.372 2.625m-4.001-4.264a9.369 9.369 0 014.263 4.001" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-lg text-gray-900 dark:text-gray-100">Gerenciar Usuários</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Visualize e edite os perfis dos usuários.</p>
                        </div>
                    </div>
                </a>

                {{-- Card de Gerenciar Categorias --}}
                <a href="{{ route('admin.categorias.index') }}" class="block p-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300">
                     <div class="flex items-center gap-4">
                        <div class="p-3 bg-indigo-100 dark:bg-indigo-900 rounded-lg">
                            <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-lg text-gray-900 dark:text-gray-100">Gerenciar Categorias</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Adicione ou edite as categorias de projetos.</p>
                        </div>
                    </div>
                </a>

            </div>
        </div>
    </div>
</x-app-layout>
