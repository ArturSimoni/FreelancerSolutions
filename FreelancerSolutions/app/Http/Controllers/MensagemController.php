<?php

namespace App\Http\Controllers;

use App\Models\Mensagem;
use App\Models\Projeto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MensagemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Exibe a interface de mensagens para um projeto específico.
     */
    public function index(Projeto $projeto)
    {
        // Autoriza a visualização do chat do projeto usando a MensagemPolicy
        // CORRIGIDO: Passa null para o argumento $mensagem e o objeto Projeto para o terceiro argumento.
        $this->authorize('view', [Mensagem::class, null, $projeto]); // Passa o modelo Mensagem, null, e o Projeto

        $user = Auth::user();

        // Carrega as mensagens do projeto
        $mensagens = Mensagem::where('projeto_id', $projeto->id)
                             ->with(['remetente', 'destinatario'])
                             ->orderBy('created_at', 'asc')
                             ->get();

        // Determina o outro participante do chat
        $outroParticipante = null;
        if ($user->id === $projeto->cliente_id) {
            if ($projeto->freelancerAceito) {
                $outroParticipante = $projeto->freelancerAceito;
            }
        } else { // Se o usuário logado é o freelancer aceito
            $outroParticipante = $projeto->cliente;
        }

        return view('mensagens.index', compact('projeto', 'mensagens', 'outroParticipante'));
    }

    /**
     * Armazena uma nova mensagem no banco de dados.
     */
    public function store(Request $request)
    {
        $request->validate([
            'projeto_id' => ['required', 'exists:projetos,id'],
            'conteudo' => ['required', 'string', 'max:1000'],
            'destinatario_id' => ['required', 'exists:users,id'],
        ]);

        $user = Auth::user();
        $projeto = Projeto::find($request->projeto_id);

        // Autoriza o envio da mensagem usando a MensagemPolicy
        // Passamos o objeto Projeto para a política para que ela possa verificar as relações.
        $this->authorize('create', [Mensagem::class, $projeto]); // Passa o modelo Mensagem e o Projeto

        // A validação do destinatário ainda é importante aqui, mesmo com a política,
        // para garantir que a mensagem está sendo enviada para o participante correto do chat.
        if ($user->id === $projeto->cliente_id) {
            if (!$projeto->freelancer_aceito_id || $request->destinatario_id != $projeto->freelancer_aceito_id) {
                return back()->with('error', 'Destinatário inválido para o cliente.');
            }
        } elseif ($projeto->freelancer_aceito_id && $user->id === $projeto->freelancer_aceito_id) {
            if ($request->destinatario_id != $projeto->cliente_id) {
                return back()->with('error', 'Destinatário inválido para o freelancer.');
            }
        } else {
            // Este caso não deveria ser alcançado se a política funcionar corretamente,
            // mas é um fallback de segurança.
            return back()->with('error', 'Você não tem permissão para enviar mensagens neste projeto.');
        }

        Mensagem::create([
            'projeto_id' => $request->projeto_id,
            'remetente_id' => $user->id,
            'destinatario_id' => $request->destinatario_id,
            'conteudo' => $request->conteudo,
        ]);

        Log::info('Mensagem enviada com sucesso. Remetente ID: ' . $user->id . ', Destinatário ID: ' . $request->destinatario_id . ', Projeto ID: ' . $projeto->id);
        return back()->with('success', 'Mensagem enviada!');
    }
}
