<?php

namespace App\Http\Controllers;

use App\Models\Candidatura;
use App\Models\Projeto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class CandidaturaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Armazena uma nova candidatura no banco de dados.
     */
    public function store(Request $request)
    {
        Log::info('Requisição de candidatura recebida.');
        Log::info('Dados da requisição: ' . json_encode($request->all()));

        if (!Auth::user()->isFreelancer()) {
            Log::warning('Tentativa de candidatura por usuário não-freelancer. User ID: ' . Auth::id());
            return back()->with('error', 'Apenas freelancers podem se candidatar a projetos.');
        }

        $dadosValidados = $request->validate([
            'projeto_id' => ['required', 'exists:projetos,id'],
            'proposta' => ['required', 'string', 'min:10'],
            'proposta_valor' => ['required', 'numeric', 'min:0'],
            'proposta_prazo' => ['required', 'integer', 'min:1'],
        ]);

        $projeto = Projeto::find($request->projeto_id);
        if (!$projeto || $projeto->status !== 'aberto') {
            Log::warning('Tentativa de candidatura a projeto inválido ou não aberto. Projeto ID: ' . $request->projeto_id);
            return back()->with('error', 'Não é possível se candidatar a este projeto.');
        }

        $existingCandidatura = Candidatura::where('projeto_id', $request->projeto_id)
                                         ->where('freelancer_id', Auth::id())
                                         ->first();

        if ($existingCandidatura) {
            Log::warning('Freelancer já se candidatou a este projeto. User ID: ' . Auth::id() . ', Projeto ID: ' . $request->projeto_id);
            return back()->with('error', 'Você já se candidatou a este projeto.');
        }

        Candidatura::create([
            'projeto_id' => $dadosValidados['projeto_id'],
            'freelancer_id' => Auth::id(), // Pega o ID do usuário logado
            'proposta' => $dadosValidados['proposta'],
            'proposta_valor' => $dadosValidados['proposta_valor'],
            'proposta_prazo' => $dadosValidados['proposta_prazo'],
            'status' => 'pendente',
        ]);

        Log::info('Candidatura criada com sucesso. User ID: ' . Auth::id() . ', Projeto ID: ' . $request->projeto_id);
        return redirect()->route('projetos.show', $projeto)->with('success', 'Sua candidatura foi enviada com sucesso!');
    }

    /**
     * Aceita uma candidatura.
     */
    public function aceitar(Candidatura $candidatura)
    {
        $this->authorize('aceitar', $candidatura);


        if ($candidatura->projeto->status !== 'aberto') {
            return back()->with('error', 'Não é possível aceitar candidaturas para este projeto no status atual.');
        }


        Candidatura::where('projeto_id', $candidatura->projeto_id)
                      ->where('id', '!=', $candidatura->id)
                      ->update(['status' => 'recusada']);

        $candidatura->update(['status' => 'aceita']);

        $candidatura->projeto->update(['status' => 'em_andamento']);

        return redirect()->route('projetos.show', $candidatura->projeto)->with('success', 'Candidatura aceita com sucesso! Projeto em andamento.');
    }

    public function recusar(Candidatura $candidatura)
    {
        $this->authorize('recusar', $candidatura);

        if ($candidatura->projeto->status !== 'aberto') {
            return back()->with('error', 'Não é possível recusar candidaturas para este projeto no status atual.');
        }

        $candidatura->update(['status' => 'recusada']);

        return redirect()->route('projetos.show', $candidatura->projeto)->with('success', 'Candidatura recusada.');
    }
}
