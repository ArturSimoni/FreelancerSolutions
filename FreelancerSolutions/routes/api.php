<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FreelancerController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProjetoController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

// Rotas públicas
Route::post('/register', [RegisteredUserController::class, 'store'])->name('api.register');
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('api.login');

Route::get('/health', function () {
    return response()->json([
        'status' => 'active',
        'service' => 'FreelancerSolutions API',
        'version' => '1.0.0',
        'timestamp' => now()->toDateTimeString()
    ]);
})->name('api.health');

// Rotas protegidas por Sanctum
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return response()->json([
            'user' => $request->user(),
            'roles' => $request->user()->roles->pluck('titulo')
        ]);
    })->name('api.user.profile');

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('api.logout');

    Route::prefix('freelancers')->name('api.freelancers.')->group(function () {
        Route::get('/', [FreelancerController::class, 'index'])->name('index');
        Route::post('/', [FreelancerController::class, 'store'])->name('store');
        Route::get('/{freelancer}', [FreelancerController::class, 'show'])->name('show');
        Route::put('/{freelancer}', [FreelancerController::class, 'update'])->name('update');
        Route::delete('/{freelancer}', [FreelancerController::class, 'destroy'])->name('destroy');
        Route::get('/{freelancer}/portfolio', [FreelancerController::class, 'portfolio'])->name('portfolio');
    });

    Route::prefix('clientes')->name('api.clientes.')->group(function () {
        Route::get('/', [ClienteController::class, 'index'])->name('index');
        Route::post('/', [ClienteController::class, 'store'])->name('store');
        Route::get('/{cliente}', [ClienteController::class, 'show'])->name('show');
        Route::put('/{cliente}', [ClienteController::class, 'update'])->name('update');
        Route::delete('/{cliente}', [ClienteController::class, 'destroy'])->name('destroy');
        Route::get('/{cliente}/projetos', [ClienteController::class, 'projetos'])->name('projetos');
    });

    Route::prefix('projetos')->name('api.projetos.')->group(function () {
        Route::get('/', [ProjetoController::class, 'index'])->name('index');
        Route::post('/', [ProjetoController::class, 'store'])->name('store');
        Route::get('/{projeto}', [ProjetoController::class, 'show'])->name('show');
        Route::put('/{projeto}', [ProjetoController::class, 'update'])->name('update');
        Route::delete('/{projeto}', [ProjetoController::class, 'destroy'])->name('destroy');
        Route::post('/{projeto}/propostas', [ProjetoController::class, 'enviarProposta'])->name('propostas');
    });
});

// Habilidades públicas
Route::get('/habilidades', function () {
    return response()->json([
        'habilidades' => [
            'Desenvolvimento Web',
            'Design Gráfico',
            'Marketing Digital',
            'Redação',
            'Tradução'
        ]
    ]);
})->name('api.habilidades');
