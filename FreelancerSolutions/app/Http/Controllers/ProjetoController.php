<?php

namespace App\Http\Controllers;

use App\Models\Projeto;
use App\Models\Categoria; // Para associar categorias ao projeto
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProjetoController extends Controller
{
    public function __construct()
    {
        // Chame o construtor da classe pai para garantir que os middlewares sejam inicializados corretamente.

        // Aplica o middleware de autenticação para todas as ações neste controlador
        $this->middleware('auth');
        // Você pode adicionar mais middlewares para autorização específica de ações
        // Por exemplo, 'can:create,App\Models\Projeto' para a ação create
    }

    /**
     * Exibe a lista de projetos (todos para admin, do cliente para cliente, abertos para freelancer).
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Projeto::query(); // Começa com uma query base

        // Filtros Comuns
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('titulo', 'like', '%' . $search . '%')
                  ->orWhere('descricao', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('categoria_id') && $request->categoria_id !== 'all') {
            $categoria_id = $request->categoria_id;
            $query->whereHas('categorias', function($q) use ($categoria_id) {
                $q->where('categoria_id', $categoria_id);
            });
        }

        if ($user->isAdministrador()) {
            $projetos = $query->with('cliente')->latest()->paginate(10); // Paginação
        } elseif ($user->isCliente()) {
            // Cliente só vê seus próprios projetos
            $projetos = $query->where('cliente_id', $user->id)
                              ->with('cliente')->latest()->paginate(10);
        } else { // Freelancer
            // Freelancer vê projetos abertos OU projetos em que sua candidatura foi aceita
            $projetos = $query->where(function($q) use ($user) {
                                $q->where('status', 'aberto')
                                  ->orWhere(function($subQ) use ($user) {
                                      $subQ->whereHas('candidaturas', function($candQ) use ($user) {
                                          $candQ->where('freelancer_id', $user->id)
                                                ->where('status', 'aceita');
                                      });
                                  });
                            })
                            ->with('cliente', 'categorias', 'candidaturas')
                            ->latest()
                            ->paginate(10); // Paginação
        }

        $categorias = Categoria::all(); // Para o filtro de categorias
        $statuses = [
            'aberto' => 'Aberto',
            'em_andamento' => 'Em Andamento',
            'aguardando_aprovacao' => 'Aguardando Aprovação',
            'concluido' => 'Concluído',
            'cancelado' => 'Cancelado',
        ];

        return view('projetos.index', compact('projetos', 'categorias', 'statuses'));
    }

    /**
     * Exibe o formulário para criar um novo projeto.
     */
    public function create()
    {
        if (!Auth::user()->isCliente()) {
            return redirect()->route('dashboard')->with('error', 'Acesso negado. Apenas clientes podem criar projetos.');
        }

        $categorias = Categoria::all(); // Carrega todas as categorias para seleção
        return view('projetos.create', compact('categorias'));
    }

    /**
     * Armazena um novo projeto no banco de dados.
     */
    public function store(Request $request)
    {
        if (!Auth::user()->isCliente()) {
            return redirect()->route('dashboard')->with('error', 'Ação não permitida.');
        }

        $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'descricao' => ['required', 'string'],
            'orcamento' => ['required', 'numeric', 'min:0'],
            'prazo' => ['required', 'date', 'after_or_equal:today'],
            'categorias' => ['array'],
            'categorias.*' => ['exists:categorias,id'],
        ]);

        $projeto = Projeto::create([
            'cliente_id' => Auth::id(),
            'titulo' => $request->titulo,
            'descricao' => $request->descricao,
            'orcamento' => $request->orcamento,
            'prazo' => $request->prazo,
            'status' => 'aberto', // Status inicial
        ]);

        // Sincroniza as categorias (relacionamento muitos-para-muitos)
        if ($request->has('categorias')) {
            $projeto->categorias()->sync($request->categorias);
        }

        return redirect()->route('projetos.index')->with('success', 'Projeto publicado com sucesso!');
    }

    /**
     * Exibe os detalhes de um projeto específico.
     */
    public function show(Projeto $projeto)
    {
        $this->authorize('view', $projeto);

        // Carrega candidaturas (apenas as aceitas se for freelancer, ou todas se for cliente)
        $projeto->load(['cliente', 'categorias', 'avaliacoes']);

        if (Auth::user()->id === $projeto->cliente_id) {
            $projeto->load('candidaturas.freelancer');
        } else if (Auth::user()->isFreelancer()) {
             // Carrega a candidatura aceita do freelancer para este projeto
             $projeto->load(['candidaturas' => function ($query) {
                 $query->where('status', 'aceita')->where('freelancer_id', Auth::id());
             }, 'candidaturas.freelancer']);
        }

        // Determina o freelancer aceito para o projeto
        $freelancerAceito = $projeto->candidaturas->where('status', 'aceita')->first();

        return view('projetos.show', compact('projeto', 'freelancerAceito'));
    }


    /**
     * Exibe o formulário para editar um projeto existente.
     */
    public function edit(Projeto $projeto)
    {
        $this->authorize('update', $projeto); // Política de autorização
        $categorias = Categoria::all();
        return view('projetos.edit', compact('projeto', 'categorias'));
    }

    /**
     * Atualiza um projeto no banco de dados.
     */
    public function update(Request $request, Projeto $projeto)
    {
        $this->authorize('update', $projeto); // Garante que apenas o dono do projeto pode editar por padrão

        $user = Auth::user();
        $rules = [
            'titulo' => ['required', 'string', 'max:255'],
            'descricao' => ['required', 'string'],
            'orcamento' => ['required', 'numeric', 'min:0'],
            'prazo' => ['required', 'date', 'after_or_equal:today'],
            'status' => ['required', Rule::in(['aberto', 'em_andamento', 'aguardando_aprovacao', 'concluido', 'cancelado'])],
            'categorias' => ['array'],
            'categorias.*' => ['exists:categorias,id'],
        ];

        // Se o usuário logado for o freelancer aceito no projeto e ele está marcando como concluído
        if ($user->isFreelancer() &&
            $projeto->freelancerAceito &&
            $projeto->freelancerAceito->freelancer_id === $user->id &&
            $request->status === 'aguardando_aprovacao' &&
            $projeto->status === 'em_andamento') {

            // Apenas o status pode ser atualizado pelo freelancer aceito para 'aguardando_aprovacao'
            $projeto->update(['status' => 'aguardando_aprovacao']);
            return redirect()->route('projetos.show', $projeto)->with('success', 'Projeto marcado como "Aguardando Aprovação" do cliente.');

        } elseif ($user->id === $projeto->cliente_id) {
            // Cliente pode mudar para "concluido" ou "em andamento" (revisão)
            $oldStatus = $projeto->status;
            $projeto->update($request->all());

            if ($request->has('categorias')) {
                $projeto->categorias()->sync($request->categorias);
            } else {
                $projeto->categorias()->sync([]);
            }

            // Lógica para quando o cliente finaliza o projeto
            if ($projeto->status === 'concluido' && $oldStatus !== 'concluido') {
                return redirect()->route('projetos.show', $projeto)->with('success', 'Projeto finalizado com sucesso! Agora você pode avaliar o freelancer.');
            }
            // Lógica para quando o cliente solicita revisão (volta para em_andamento)
            if ($request->status === 'em_andamento' && $oldStatus === 'aguardando_aprovacao') {
                 return redirect()->route('projetos.show', $projeto)->with('success', 'Solicitação de revisão enviada. O projeto voltou para "Em Andamento".');
            }


            return redirect()->route('projetos.show', $projeto)->with('success', 'Projeto atualizado com sucesso!');
        }

        return back()->with('error', 'Ação não permitida para o seu perfil ou status atual do projeto.');
    }


    /**
     * Remove um projeto do banco de dados.
     */
    public function destroy(Projeto $projeto)
    {
        $this->authorize('delete', $projeto); // Política de autorização
        $projeto->delete();
        return redirect()->route('projetos.index')->with('success', 'Projeto excluído com sucesso!');
    }
}
