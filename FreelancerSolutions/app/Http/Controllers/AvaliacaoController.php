<?php

namespace App\Http\Controllers;

use App\Models\AvaliacaoServico;
use App\Models\Projeto;
use App\Models\User; // Importar o modelo User
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Para logs de depuração

class AvaliacaoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Exibe o formulário para criar uma nova avaliação para um projeto.
     */
    public function create(Projeto $projeto)
    {
        $user = Auth::user();
        $avaliado = null;
        $tipo_avaliacao = null;

        // Lógica para determinar quem está avaliando quem
        if ($user->id === $projeto->cliente_id) {
            // Cliente avalia o freelancer
            if (!$projeto->freelancerAceito) {
                return redirect()->route('projetos.show', $projeto)->with('error', 'Não há freelancer aceito para avaliar neste projeto.');
            }
            $avaliado = $projeto->freelancerAceito->freelancer; // O freelancer aceito é o avaliado
            $tipo_avaliacao = 'cliente_para_freelancer';
        } elseif ($projeto->freelancerAceito && $user->id === $projeto->freelancerAceito->freelancer_id) {
            // Freelancer avalia o cliente
            $avaliado = $projeto->cliente; // O cliente é o avaliado
            $tipo_avaliacao = 'freelancer_para_cliente';
        } else {
            // Se o usuário não é o cliente nem o freelancer aceito, não pode avaliar
            return redirect()->route('projetos.show', $projeto)->with('error', 'Você não tem permissão para avaliar este projeto.');
        }

        // Verifica se o projeto está concluído antes de permitir a avaliação
        if ($projeto->status !== 'concluido') {
            return redirect()->route('projetos.show', $projeto)->with('error', 'Este projeto ainda não foi concluído e não pode ser avaliado.');
        }

        // Verifica se já existe uma avaliação do tipo específico feita pelo usuário para este projeto
        $avaliacaoExistente = AvaliacaoServico::where('projeto_id', $projeto->id)
                                              ->where('avaliador_id', $user->id)
                                              ->where('tipo_avaliacao', $tipo_avaliacao)
                                              ->first();

        if ($avaliacaoExistente) {
            return redirect()->route('projetos.show', $projeto)->with('error', 'Você já avaliou este projeto para esta função.');
        }

        return view('avaliacoes.create', compact('projeto', 'avaliado', 'tipo_avaliacao'));
    }

    /**
     * Armazena uma nova avaliação no banco de dados.
     */
    public function store(Request $request)
    {
        Log::debug('AvaliacaoController@store: Iniciando processo de armazenamento de avaliação.');
        Log::debug('AvaliacaoController@store: Dados recebidos: ' . json_encode($request->all()));

        $request->validate([
            'projeto_id' => ['required', 'exists:projetos,id'],
            'avaliado_id' => ['required', 'exists:users,id'],
            'nota' => ['required', 'integer', 'min:1', 'max:5'],
            'comentario' => ['nullable', 'string', 'max:1000'],
            'tipo_avaliacao' => ['required', 'string', 'in:cliente_para_freelancer,freelancer_para_cliente'],
        ]);

        $user = Auth::user();
        $projeto = Projeto::findOrFail($request->projeto_id);
        $avaliado = User::findOrFail($request->avaliado_id);

        Log::debug('AvaliacaoController@store: User Logado ID: ' . $user->id . ', Projeto ID: ' . $projeto->id . ', Avaliado ID: ' . $avaliado->id . ', Tipo Avaliação: ' . $request->tipo_avaliacao);

        // Lógica de autorização para garantir que o avaliador e o avaliado são os corretos para o projeto
        if ($request->tipo_avaliacao === 'cliente_para_freelancer') {
            // Cliente deve ser o avaliador e o avaliado deve ser o freelancer aceito
            if ($user->id !== $projeto->cliente_id || ($projeto->freelancerAceito && $avaliado->id !== $projeto->freelancerAceito->freelancer_id)) {
                Log::warning('AvaliacaoController@store: Tentativa de avaliação não autorizada (cliente_para_freelancer).');
                return back()->with('error', 'Ação não autorizada para esta avaliação.');
            }
        } elseif ($request->tipo_avaliacao === 'freelancer_para_cliente') {
            // Freelancer aceito deve ser o avaliador e o avaliado deve ser o cliente
            if (($projeto->freelancerAceito && $user->id !== $projeto->freelancerAceito->freelancer_id) || $avaliado->id !== $projeto->cliente_id) {
                Log::warning('AvaliacaoController@store: Tentativa de avaliação não autorizada (freelancer_para_cliente).');
                return back()->with('error', 'Ação não autorizada para esta avaliação.');
            }
        }

        // Verifica se o projeto está concluído
        if ($projeto->status !== 'concluido') {
            Log::warning('AvaliacaoController@store: Tentativa de avaliar projeto não concluído. Projeto ID: ' . $projeto->id);
            return back()->with('error', 'O projeto deve estar concluído para ser avaliado.');
        }

        // Impede múltiplas avaliações do mesmo tipo pelo mesmo avaliador para o mesmo projeto
        $avaliacaoExistente = AvaliacaoServico::where('projeto_id', $projeto->id)
                                              ->where('avaliador_id', $user->id)
                                              ->where('tipo_avaliacao', $request->tipo_avaliacao)
                                              ->first();
        if ($avaliacaoExistente) {
            Log::warning('AvaliacaoController@store: Tentativa de avaliação duplicada. Projeto ID: ' . $projeto->id . ', Avaliador ID: ' . $user->id . ', Tipo: ' . $request->tipo_avaliacao);
            return back()->with('error', 'Você já enviou uma avaliação para este projeto nesta função.');
        }

        try {
            $avaliacao = AvaliacaoServico::create([
                'projeto_id' => $request->projeto_id,
                'avaliador_id' => $user->id,
                'avaliado_id' => $request->avaliado_id,
                'nota' => $request->nota,
                'comentario' => $request->comentario,
                'tipo_avaliacao' => $request->tipo_avaliacao,
            ]);
            Log::info('Avaliação salva com sucesso! Avaliação ID: ' . $avaliacao->id);
            return redirect()->route('projetos.show', $projeto)->with('success', 'Avaliação enviada com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao salvar avaliação: ' . $e->getMessage());
            return back()->with('error', 'Ocorreu um erro ao enviar sua avaliação. Tente novamente.');
        }
    }
}
