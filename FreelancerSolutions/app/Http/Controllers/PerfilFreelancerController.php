<?php

namespace App\Http\Controllers;

use App\Models\PerfilFreelancer;
use App\Models\Habilidade;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;

class PerfilFreelancerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['show']);
    }


    public function edit()
    {
        $user = Auth::user();

        Log::info("Acedendo PerfilFreelancerController@edit para o utilizador ID: " . $user->id);
        Log::info("Perfil do utilizador: " . $user->perfil);
        Log::info("É freelancer? " . ($user->isFreelancer() ? 'Sim' : 'Não'));
        Log::info("Pode aceder ao dashboard de freelancer? " . (Gate::allows('access-freelancer-dashboard') ? 'Sim' : 'Não'));

        if (!$user->isFreelancer()) {
            return redirect()->route('dashboard')->with('error', 'Acesso negado. Apenas freelancers podem editar o seu perfil.');
        }

        // Garante que o perfil do freelancer existe, criando um se necessário
        $perfilFreelancer = $user->perfilFreelancer()->firstOrCreate(
            ['user_id' => $user->id]
        );

        $habilidadesDisponiveis = Habilidade::orderBy('nome')->get();

        return view('freelancer.profile.edit', compact('user', 'perfilFreelancer', 'habilidadesDisponiveis'));
    }


    public function update(Request $request)
    {
        $user = Auth::user();

        if (!$user->isFreelancer()) {
            return redirect()->route('dashboard')->with('error', 'Ação não permitida.');
        }

        $perfilFreelancer = $user->perfilFreelancer;

        if (!$perfilFreelancer) {
            return redirect()->route('freelancer.profile.edit')->with('error', 'O seu perfil de freelancer não foi encontrado. Tente novamente.');
        }

        // 1. VALIDAÇÃO CORRIGIDA para usar os nomes certos ('bio', 'portifolio_url')
        $validatedData = $request->validate([
            'bio' => ['nullable', 'string', 'max:5000'],
            'preco_hora' => ['nullable', 'numeric', 'min:0'],
            'portifolio_url' => ['nullable', 'url', 'max:255'],
            'habilidades' => ['nullable', 'array'],
            'habilidades.*' => ['exists:habilidades,id'], // Garante que os IDs de habilidade existem
        ]);

        // 2. ATUALIZAÇÃO SIMPLIFICADA
        //    Passamos apenas os dados validados para o update.
        $perfilFreelancer->update($validatedData);

        // 3. SINCRONIZAÇÃO DAS HABILIDADES
        //    Usa 'sync' que remove as antigas e adiciona as novas selecionadas.
        $perfilFreelancer->habilidades()->sync($request->habilidades ?? []);

        return redirect()->route('freelancer.profile.edit')->with('status', 'profile-updated');
    }


    public function show(User $user)
    {
        if (!$user->isFreelancer() || !$user->perfilFreelancer) {
            return redirect()->route('dashboard')->with('error', 'Perfil de freelancer não encontrado.');
        }

        $perfilFreelancer = $user->perfilFreelancer->load('habilidades', 'user.avaliacoesRecebidas.avaliador');

        $mediaAvaliacoes = $perfilFreelancer->user->avaliacoesRecebidas()
                                          ->where('tipo_avaliacao', 'cliente_para_freelancer')
                                          ->avg('nota');

        // Esta parte pode ser otimizada, mas por enquanto funciona para obter os projetos
        $projetosConcluidos = Projeto::whereHas('candidaturas', function ($query) use ($user) {
            $query->where('freelancer_id', $user->id)->where('status', 'aceita');
        })->where('status', 'concluido')->with('cliente', 'categorias')->get();


        return view('freelancer.show', compact('user', 'perfilFreelancer', 'projetosConcluidos', 'mediaAvaliacoes'));
    }
}
