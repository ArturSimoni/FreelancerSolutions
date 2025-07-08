<?php

namespace App\Http\Controllers;

use App\Models\PerfilFreelancer;
use App\Models\Habilidade;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate; // Importe a fachada Gate

class PerfilFreelancerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['show']);
    }

    /**
     * Exibe o formulário para editar o perfil do freelancer logado.
     */
    public function edit()
    {
        $user = Auth::user();

        // --- INÍCIO DA DEPURAÇÃO (Manter para diagnóstico, remover após resolver) ---
        Log::info("Acedendo PerfilFreelancerController@edit para o utilizador ID: " . $user->id);
        Log::info("Perfil do utilizador: " . $user->perfil);
        Log::info("É freelancer? " . ($user->isFreelancer() ? 'Sim' : 'Não'));
        Log::info("Pode aceder ao dashboard de freelancer? " . (Gate::allows('access-freelancer-dashboard') ? 'Sim' : 'Não'));

        // Se o erro 403 ainda ocorrer, este dd() pode não ser atingido.
        // Se for atingido e mostrar "Não" para o Gate, o problema está na definição do Gate ou no perfil do utilizador.
        // Se for atingido e mostrar "Sim" para o Gate, o problema pode ser no middleware da rota ou noutro local.
        // dd([
        //     'user_id' => $user->id,
        //     'user_perfil' => $user->perfil,
        //     'is_freelancer_method' => $user->isFreelancer(),
        //     'can_access_freelancer_dashboard_gate' => Gate::allows('access-freelancer-dashboard'),
        //     'mensagem' => 'Verifique estes valores.'
        // ]);
        // --- FIM DA DEPURAÇÃO ---


        // Garante que apenas freelancers podem aceder a esta página.
        if (!$user->isFreelancer()) {
            return redirect()->route('dashboard')->with('error', 'Acesso negado. Apenas freelancers podem editar o seu perfil.');
        }

        $perfilFreelancer = $user->perfilFreelancer;

        if (!$perfilFreelancer) {
            $perfilFreelancer = PerfilFreelancer::create(['user_id' => $user->id]);
            Log::info("Perfil de freelancer criado para o utilizador ID: " . $user->id);
        }

        $habilidades = Habilidade::all();
        $habilidadesSelecionadas = $perfilFreelancer->habilidades->pluck('id')->toArray();

        // CORRIGIDO: Alterado o caminho da view para 'freelancer.edit'
        return view('freelancer.edit', compact('perfilFreelancer', 'habilidades', 'habilidadesSelecionadas'));
    }

    /**
     * Atualiza o perfil do freelancer logado no banco de dados.
     */
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

        $request->validate([
            'titulo_profissional' => ['nullable', 'string', 'max:255'],
            'biografia' => ['nullable', 'string'],
            'preco_hora' => ['nullable', 'numeric', 'min:0'],
            'portfolio_url' => ['nullable', 'url', 'max:255'],
            'habilidades' => ['array'],
            'habilidades.*' => ['exists:habilidades,id'],
        ]);

        $perfilFreelancer->update($request->only([
            'titulo_profissional',
            'biografia',
            'preco_hora',
            'portfolio_url',
        ]));

        if ($request->has('habilidades')) {
            $perfilFreelancer->habilidades()->sync($request->habilidades);
        } else {
            $perfilFreelancer->habilidades()->sync([]);
        }

        return redirect()->route('freelancer.profile.edit')->with('success', 'Perfil atualizado com sucesso!');
    }

    /**
     * Exibe o perfil público de um freelancer.
     */
    public function show(User $user)
    {
        if (!$user->isFreelancer()) {
            return redirect()->route('dashboard')->with('error', 'Este utilizador não é um freelancer.');
        }

        $perfilFreelancer = $user->perfilFreelancer;

        if (!$perfilFreelancer) {
            return redirect()->route('dashboard')->with('error', 'Perfil de freelancer não encontrado para este utilizador.');
        }

        $perfilFreelancer->load('habilidades', 'user.avaliacoesRecebidas.avaliador', 'user.avaliacoesRecebidas.projeto');

        $projetosConcluidos = $perfilFreelancer->user->projetosAceitos()
                                                   ->where('status', 'concluido')
                                                   ->with('cliente')
                                                   ->get();

        $mediaAvaliacoes = $perfilFreelancer->user->avaliacoesRecebidas()
                                                 ->where('tipo_avaliacao', 'cliente_para_freelancer')
                                                 ->avg('nota');

        return view('perfil_freelancer.show', compact('perfilFreelancer', 'projetosConcluidos', 'mediaAvaliacoes', 'user'));
    }
}
