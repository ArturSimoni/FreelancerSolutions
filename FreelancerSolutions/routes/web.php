<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\PerfilFreelancerController;
use App\Http\Controllers\ProjetoController;
use App\Http\Controllers\CandidaturaController;
use App\Http\Controllers\MensagemController;
use App\Http\Controllers\AvaliacaoController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\CategoriaController as AdminCategoriaController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// --- ROTAS PÚBLICAS ---

// Rota da página inicial
Route::get('/', function () {
    return view('welcome');
});

// Rotas de autenticação do Laravel Breeze (login, logout, reset de senha, etc.)
require __DIR__ . '/auth.php';

// --- ROTAS DE REGISTRO PERSONALIZADAS ---
Route::middleware('guest')->group(function () {
    // Rotas para o registro de Cliente
    Route::get('/register/cliente', [RegisteredUserController::class, 'createClient'])->name('register.cliente');
    Route::post('/register/cliente', [RegisteredUserController::class, 'storeClient']);

    // Rotas para o registro de Freelancer
    Route::get('/register/freelancer', [RegisteredUserController::class, 'createFreelancer'])->name('register.freelancer');
    Route::post('/register/freelancer', [RegisteredUserController::class, 'storeFreelancer']);
});

// --- ROTAS PROTEGIDAS (REQUER AUTENTICAÇÃO E VERIFICAÇÃO DE E-MAIL) ---
Route::middleware(['auth', 'verified'])->group(function () {

    // Redirecionamento para Dashboards específicos com base no perfil do utilizador
    Route::get('/dashboard', function () {
        $user = Auth::user();
        if ($user->isAdministrador()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isFreelancer()) {
            return redirect()->route('freelancer.dashboard');
        } else {
            return view('dashboard'); // Dashboard padrão para clientes
        }
    })->name('dashboard');

    // Gestão de Perfil Padrão do Breeze (Nome, Email, Senha)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- ROTAS GERAIS PARA CLIENTES E FREELANCERS ---

    // Rotas para Gerir Projetos (CRUD completo, exceto destroy que é separado)
    Route::resource('projetos', ProjetoController::class)->except(['destroy']);
    Route::delete('/projetos/{projeto}', [ProjetoController::class, 'destroy'])->name('projetos.destroy');

    // Rotas para Candidaturas (Enviar, Aceitar, Recusar)
    Route::post('/candidaturas', [CandidaturaController::class, 'store'])->name('candidaturas.store');
    Route::post('/candidaturas/{candidatura}/aceitar', [CandidaturaController::class, 'aceitar'])->name('candidaturas.aceitar');
    Route::post('/candidaturas/{candidatura}/recusar', [CandidaturaController::class, 'recusar'])->name('candidaturas.recusar');

    // Rotas para Avaliações
    Route::get('/projetos/{projeto}/avaliar', [AvaliacaoController::class, 'create'])->name('avaliacoes.create');
    Route::post('/avaliacoes', [AvaliacaoController::class, 'store'])->name('avaliacoes.store');

    // Rotas para Mensagens (dentro do contexto de um projeto)
    Route::get('/projetos/{projeto}/mensagens', [MensagemController::class, 'index'])->name('mensagens.index');
    Route::post('/mensagens', [MensagemController::class, 'store'])->name('mensagens.store');

    // Rotas de Notificações
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{notification}/read-and-redirect', [NotificationController::class, 'markAsReadAndRedirect'])
    ->name('notifications.markAsReadAndRedirect');
    Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');

    // --- GRUPO DE ROTAS ESPECÍFICAS PARA FREELANCERS ---
    Route::prefix('freelancer')->middleware('can:access-freelancer-dashboard,App\Models\User')->group(function () {
        Route::get('/dashboard', function () {
            return view('freelancer.dashboard');
        })->name('freelancer.dashboard');

        // Gestão do Perfil de Freelancer (bio, portfólio, preço/hora, habilidades)
        Route::get('/profile', [PerfilFreelancerController::class, 'edit'])->name('freelancer.profile.edit');
        Route::put('/profile', [PerfilFreelancerController::class, 'update'])->name('freelancer.profile.update');
    });

    // --- GRUPO DE ROTAS ESPECÍFICAS PARA ADMINISTRADORES ---
    Route::prefix('admin')->middleware('can:access-admin-dashboard,App\Models\User')->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');

        // Gestão de Utilizadores (Admin)
        Route::resource('users', AdminUserController::class)->except(['create', 'store', 'show']);

        // Gestão de Categorias (Admin)
        Route::resource('categorias', AdminCategoriaController::class);
    });

    // --- ROTA DE PERFIL PÚBLICO DE FREELANCER (ACESSÍVEL POR CLIENTES E OUTROS) ---
    Route::get('/freelancers/{user}', [PerfilFreelancerController::class, 'show'])->name('freelancers.show');
});
