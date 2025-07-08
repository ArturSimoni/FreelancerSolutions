<?php

namespace App\Http\Controllers;

use App\Models\Candidatura;
use App\Models\Projeto; // Para associar candidaturas a projetos
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Para logs de depuração
use Illuminate\Validation\Rule; // Adicione esta linha para importar a classe Rule

class CandidaturaController extends Controller
{
    public function __construct()
    {
        // Aplica o middleware de autenticação para todas as ações neste controlador
        $this->middleware('auth');
    }

    /**
     * Armazena uma nova candidatura no banco de dados.
     */
    public function store(Request $request)
    {
        // --- INÍCIO DA DEPURAÇÃO ---
        Log::info('Requisição de candidatura recebida.');
        Log::info('Dados da requisição: ' . json_encode($request->all()));

        // ESTA LINHA VAI PARAR A EXECUÇÃO E MOSTRAR OS DADOS DA REQUISIÇÃO.
        // COPIE O OUTPUT E COLE-O AQUI PARA EU ANALISAR.
        // dd($request->all());

        // Verifique se o usuário é um freelancer
        if (!Auth::user()->isFreelancer()) {
            Log::warning('Tentativa de candidatura por usuário não-freelancer. User ID: ' . Auth::id());
            return back()->with('error', 'Apenas freelancers podem se candidatar a projetos.');
        }

        // --- FIM DA DEPURAÇÃO ---

        $request->validate([
            'projeto_id' => ['required', 'exists:projetos,id'],
            'freelancer_id' => ['required', 'exists:users,id', Rule::in([Auth::id()])], // Garante que o ID do freelancer é o do usuário logado
            'proposta' => ['required', 'string', 'min:10'],
            'proposta_valor' => ['required', 'numeric', 'min:0'], // Validação para proposta_valor
            'proposta_prazo' => ['required', 'integer', 'min:1'], // Validação para proposta_prazo
        ]);

        // Verifique se o projeto existe e está aberto
        $projeto = Projeto::find($request->projeto_id);
        if (!$projeto || $projeto->status !== 'aberto') {
            Log::warning('Tentativa de candidatura a projeto inválido ou não aberto. Projeto ID: ' . $request->projeto_id);
            return back()->with('error', 'Não é possível se candidatar a este projeto.');
        }

        // Verifique se o freelancer já se candidatou a este projeto
        $existingCandidatura = Candidatura::where('projeto_id', $request->projeto_id)
                                          ->where('freelancer_id', Auth::id())
                                          ->first();

        if ($existingCandidatura) {
            Log::warning('Freelancer já se candidatou a este projeto. User ID: ' . Auth::id() . ', Projeto ID: ' . $request->projeto_id);
            return back()->with('error', 'Você já se candidatou a este projeto.');
        }

        Candidatura::create([
            'projeto_id' => $request->projeto_id,
            'freelancer_id' => Auth::id(), // Use Auth::id() para garantir consistência
            'proposta' => $request->proposta, // Incluído
            'proposta_valor' => $request->proposta_valor, // Incluído
            'proposta_prazo' => $request->proposta_prazo, // Incluído
            'status' => 'pendente', // Status inicial da candidatura
        ]);

        Log::info('Candidatura criada com sucesso. User ID: ' . Auth::id() . ', Projeto ID: ' . $request->projeto_id);
        return redirect()->route('projetos.show', $projeto)->with('success', 'Sua candidatura foi enviada com sucesso!');
    }

    /**
     * Aceita uma candidatura.
     */
    public function aceitar(Candidatura $candidatura)
    {
        // Autorização: Apenas o cliente do projeto pode aceitar candidaturas
        $this->authorize('aceitar', $candidatura);

        // Verifica se o projeto está aberto
        if ($candidatura->projeto->status !== 'aberto') {
            return back()->with('error', 'Não é possível aceitar candidaturas para este projeto no status atual.');
        }

        // Recusa todas as outras candidaturas para o mesmo projeto
        Candidatura::where('projeto_id', $candidatura->projeto_id)
                   ->where('id', '!=', $candidatura->id)
                   ->update(['status' => 'recusada']);

        // Aceita a candidatura selecionada
        $candidatura->update(['status' => 'aceita']);

        // Atualiza o status do projeto para 'em_andamento'
        $candidatura->projeto->update(['status' => 'em_andamento']);

        return redirect()->route('projetos.show', $candidatura->projeto)->with('success', 'Candidatura aceita com sucesso! Projeto em andamento.');
    }

    /**
     * Recusa uma candidatura.
     */
    public function recusar(Candidatura $candidatura)
    {
        // Autorização: Apenas o cliente do projeto pode recusar candidaturas
        $this->authorize('recusar', $candidatura);

        // Verifica se o projeto está aberto
        if ($candidatura->projeto->status !== 'aberto') {
            return back()->with('error', 'Não é possível recusar candidaturas para este projeto no status atual.');
        }

        $candidatura->update(['status' => 'recusada']);

        return redirect()->route('projetos.show', $candidatura->projeto)->with('success', 'Candidatura recusada.');
    }
}
