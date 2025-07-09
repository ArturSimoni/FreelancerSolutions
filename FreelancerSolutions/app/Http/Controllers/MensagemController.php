<?php

namespace App\Http\Controllers;

use App\Models\Mensagem;
use App\Models\Projeto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class MensagemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Exibe a interface de mensagens para um projeto específico.
     */
    public function index(Projeto $projeto, User $destinatario)
    {
        $this->authorize('view', $projeto);

        $outroParticipante = $destinatario;
        $user = Auth::user();

        $mensagens = Mensagem::where('projeto_id', $projeto->id)
            ->where(function ($query) use ($user, $outroParticipante) {
                $query->where('remetente_id', $user->id)
                    ->where('destinatario_id', $outroParticipante->id);
            })
            ->orWhere(function ($query) use ($user, $outroParticipante) {
                $query->where('remetente_id', $outroParticipante->id)
                    ->where('destinatario_id', $user->id);
            })
            ->with(['remetente', 'destinatario'])
            ->orderBy('created_at', 'asc')
            ->get();

        return view('mensagens.index', compact('projeto', 'mensagens', 'outroParticipante'));
    }

    /**
     * Armazena uma nova mensagem no banco de dados.
     */
    public function store(Request $request)
    {

        $dadosValidados = $request->validate([
            'projeto_id' => ['required', 'exists:projetos,id'],
            'conteudo' => ['required', 'string', 'max:1000'],
            'destinatario_id' => ['required', 'exists:users,id'],
        ]);

        $projeto = Projeto::findOrFail($dadosValidados['projeto_id']);
        $user = Auth::user();


        $this->authorize('create', [Mensagem::class, $projeto]);
        Mensagem::create([
            'projeto_id' => $dadosValidados['projeto_id'],
            'remetente_id' => $user->id,
            'destinatario_id' => $dadosValidados['destinatario_id'],
            'conteudo' => $dadosValidados['conteudo'],
        ]);

        Log::info('Mensagem enviada com sucesso. Remetente ID: ' . $user->id . ', Destinatário ID: ' . $dadosValidados['destinatario_id'] . ', Projeto ID: ' . $projeto->id);

        return back()->with('success', 'Mensagem enviada!');
    }
}
