<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p>{{ __("Você está logado como Administrador!") }}</p>
                    <hr class="my-4">
                    <h5>Opções de Gerenciamento:</h5>
                    <ul class="list-group">
                        <li class="list-group-item">
                            <a href="{{ route('admin.users.index') }}">Gerenciar Usuários</a>
                        </li>
                        <li class="list-group-item">
                            <a href="{{ route('admin.categorias.index') }}">Gerenciar Categorias de Projetos</a>
                        </li>
                        {{-- Adicione mais links de gerenciamento aqui --}}
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
