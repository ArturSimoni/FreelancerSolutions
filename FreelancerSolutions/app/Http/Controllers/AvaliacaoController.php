<?php

namespace App\Http\Controllers;

use App\Models\AvaliacaoServico; // Certifique-se de que este é o nome correto do seu modelo de avaliação
use App\Models\Projeto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class AvaliacaoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Exibe o formulário para criar uma nova avaliação.
     */
    public function create(Projeto $projeto)
    {
        $user = Auth::user();
        $tipo_avaliacao = ''; // Inicializa a variável
        $avaliado = null; // Inicializa a variável $avaliado

        // Lógica de autorização para acessar o formulário de avaliação
        // Apenas se o projeto estiver concluído e o usuário for o cliente ou o freelancer aceito
        if ($projeto->status !== 'concluido') {
            return redirect()->route('projetos.show', $projeto)->with('error', 'O projeto deve estar concluído para ser avaliado.');
        }

        // Cliente avalia freelancer
        if ($user->id === $projeto->cliente_id) {
            if (!$projeto->freelancerAceito) {
                return redirect()->route('projetos.show', $projeto)->with('error', 'Não há freelancer aceite para avaliar neste projeto.');
            }
            // Verifica se o cliente já avaliou este freelancer para este projeto
            if ($projeto->avaliacoes->where('avaliador_id', $user->id)->where('tipo_avaliacao', 'cliente_para_freelancer')->first()) {
                return redirect()->route('projetos.show', $projeto)->with('error', 'Você já avaliou este freelancer para este projeto.');
            }
            $tipo_avaliacao = 'cliente_para_freelancer'; // Define o tipo de avaliação
            $avaliado = $projeto->freelancerAceito; // Define quem será avaliado
        }
        // Freelancer avalia cliente
        elseif ($projeto->freelancerAceito && $user->id === $projeto->freelancerAceito->id) {
            // Verifica se o freelancer já avaliou este cliente para este projeto
            if ($projeto->avaliacoes->where('avaliador_id', $user->id)->where('tipo_avaliacao', 'freelancer_para_cliente')->first()) {
                return redirect()->route('projetos.show', $projeto)->with('error', 'Você já avaliou este cliente para este projeto.');
            }
            $tipo_avaliacao = 'freelancer_para_cliente'; // Define o tipo de avaliação
            $avaliado = $projeto->cliente; // Define quem será avaliado
        } else {
            return redirect()->route('projetos.show', $projeto)->with('error', 'Você não tem permissão para avaliar este projeto.');
        }

        // CORRIGIDO: As variáveis $tipo_avaliacao e $avaliado são passadas para a view
        return view('avaliacaoservico.create', compact('projeto', 'tipo_avaliacao', 'avaliado'));
    }

    /**
     * Armazena uma nova avaliação no banco de dados.
     */
    public function store(Request $request)
    {
        $request->validate([
            'projeto_id' => ['required', 'exists:projetos,id'],
            'avaliador_id' => ['required', 'exists:users,id'],
            'avaliado_id' => ['required', 'exists:users,id'],
            'nota' => ['required', 'integer', 'min:1', 'max:5'],
            'comentario' => ['nullable', 'string', 'max:1000'],
            'tipo_avaliacao' => ['required', Rule::in(['cliente_para_freelancer', 'freelancer_para_cliente'])],
        ]);

        $projeto = Projeto::find($request->projeto_id);
        $user = Auth::user();

        // Re-verifica a autorização antes de armazenar para maior segurança
        if ($projeto->status !== 'concluido') {
            return back()->with('error', 'O projeto deve estar concluído para ser avaliado.');
        }

        $isAuthorized = false;
        if ($user->id === $projeto->cliente_id && $request->tipo_avaliacao === 'cliente_para_freelancer' && $request->avaliado_id == $projeto->freelancer_aceito_id) {
            // Cliente avaliando freelancer
            if (!$projeto->avaliacoes->where('avaliador_id', $user->id)->where('tipo_avaliacao', 'cliente_para_freelancer')->first()) {
                $isAuthorized = true;
            }
        } elseif ($projeto->freelancerAceito && $user->id === $projeto->freelancerAceito->id && $request->tipo_avaliacao === 'freelancer_para_cliente' && $request->avaliado_id == $projeto->cliente_id) {
            // Freelancer avaliando cliente
            if (!$projeto->avaliacoes->where('avaliador_id', $user->id)->where('tipo_avaliacao', 'freelancer_para_cliente')->first()) {
                $isAuthorized = true;
            }
        }

        if (!$isAuthorized) {
            Log::warning('Tentativa de avaliação não autorizada. User ID: ' . $user->id . ', Projeto ID: ' . $projeto->id);
            return back()->with('error', 'Você não tem permissão para realizar esta avaliação ou já a realizou.');
        }

        AvaliacaoServico::create([
            'projeto_id' => $request->projeto_id,
            'avaliador_id' => $request->avaliador_id,
            'avaliado_id' => $request->avaliado_id,
            'nota' => $request->nota,
            'comentario' => $request->comentario,
            'tipo_avaliacao' => $request->tipo_avaliacao,
        ]);

        return redirect()->route('projetos.show', $projeto)->with('success', 'Avaliação enviada com sucesso!');
    }
}
