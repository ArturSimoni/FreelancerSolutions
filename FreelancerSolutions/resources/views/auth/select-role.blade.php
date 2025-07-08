@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-lg shadow">
        <h2 class="text-center text-3xl font-extrabold text-gray-900">
            Selecionar Tipo de Usuário
        </h2>

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('role.process') }}">
            @csrf
            <div class="mt-6">
                <label for="role" class="block text-sm font-medium text-gray-700">Escolha o seu papel</label>
                <select id="role" name="role" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                    <option value="" disabled selected>-- Selecione uma opção --</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->titulo }}">{{ ucfirst($role->titulo) }}</option>
                    @endforeach
                </select>
                @error('role')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-6">
                <button type="submit"
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    Confirmar Seleção
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
