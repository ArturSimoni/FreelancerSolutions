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

Route::get('/', function () {
    return view('welcome');
});

require __DIR__ . '/auth.php';

Route::middleware('guest')->group(function () {
    Route::get('/register/cliente', [RegisteredUserController::class, 'createClient'])->name('register.cliente');
    Route::post('/register/cliente', [RegisteredUserController::class, 'storeClient']);

    Route::get('/register/freelancer', [RegisteredUserController::class, 'createFreelancer'])->name('register.freelancer');
    Route::post('/register/freelancer', [RegisteredUserController::class, 'storeFreelancer']);
});

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', function () {
        $user = Auth::user();
        if ($user->isAdministrador()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isFreelancer()) {
            return redirect()->route('freelancer.dashboard');
        } else {
            return view('dashboard');
        }
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('projetos', ProjetoController::class)->except(['destroy']);
    Route::delete('/projetos/{projeto}', [ProjetoController::class, 'destroy'])->name('projetos.destroy');

    Route::post('/candidaturas', [CandidaturaController::class, 'store'])->name('candidaturas.store');
    Route::post('/candidaturas/{candidatura}/aceitar', [CandidaturaController::class, 'aceitar'])->name('candidaturas.aceitar');
    Route::post('/candidaturas/{candidatura}/recusar', [CandidaturaController::class, 'recusar'])->name('candidaturas.recusar');

    Route::get('/projetos/{projeto}/avaliar', [AvaliacaoController::class, 'create'])->name('avaliacoes.create');
    Route::post('/avaliacoes', [AvaliacaoController::class, 'store'])->name('avaliacoes.store');

    Route::get('/projetos/{projeto}/mensagens/{destinatario}', [MensagemController::class, 'index'])->name('mensagens.index');
    Route::post('/mensagens', [MensagemController::class, 'store'])->name('mensagens.store');

    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{notification}/read-and-redirect', [NotificationController::class, 'markAsReadAndRedirect'])
    ->name('notifications.markAsReadAndRedirect');
    Route::post('/notifications/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');

    Route::prefix('freelancer')->name('freelancer.')->middleware('can:access-freelancer-dashboard,App\Models\User')->group(function () {
        Route::get('/dashboard', function () {
            return view('freelancer.dashboard');
        })->name('dashboard');

        Route::get('/profile', [PerfilFreelancerController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [PerfilFreelancerController::class, 'update'])->name('profile.update');
    });


    Route::prefix('admin')->name('admin.')->middleware('can:access-admin-dashboard,App\Models\User')->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        Route::resource('users', AdminUserController::class)->except(['create', 'store', 'show']);
        Route::resource('categorias', AdminCategoriaController::class);
    });

    Route::get('/freelancers/{user}', [PerfilFreelancerController::class, 'show'])->name('freelancers.show');
});
