<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PerfilFreelancer;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Exibe a view de registro padrão do Breeze.
     * Esta rota é geralmente acessada via '/register'.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Processa o registro de um novo usuário via rota padrão '/register'.
     * Por padrão, registra como 'cliente'.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'perfil' => 'cliente',
        ]);

        event(new Registered($user));

        Auth::login($user);

        // --- SOLUÇÃO DEFINITIVA: Recarregar o utilizador da base de dados ---
        Auth::user()->refresh();
        // --- FIM DA SOLUÇÃO ---

        return redirect(RouteServiceProvider::HOME())->with('success', 'Cadastro realizado com sucesso!');
    }


    /**
     * Exibe a view de registro para Clientes.
     * Esta rota é acessada via '/register/cliente'.
     */
    public function createClient(): View
    {
        return view('auth.register-cliente');
    }

    /**
     * Processa o registro de um novo usuário como Cliente.
     * Esta rota é acessada via POST para '/register/cliente'.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function storeClient(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'perfil' => 'cliente',
        ]);

        event(new Registered($user));

        Auth::login($user);

        // --- SOLUÇÃO DEFINITIVA: Recarregar o utilizador da base de dados ---
        Auth::user()->refresh();
        // --- FIM DA SOLUÇÃO ---

        return redirect(RouteServiceProvider::HOME())->with('success', 'Cadastro de cliente realizado com sucesso!');
    }

    /**
     * Exibe a view de registro para Freelancers.
     * Esta rota é acessada via '/register/freelancer'.
     */
    public function createFreelancer(): View
    {
        return view('auth.register-freelancer');
    }

    /**
     * Processa o registro de um novo usuário como Freelancer.
     * Esta rota é acessada via POST para '/register/freelancer'.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function storeFreelancer(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'perfil' => 'freelancer',
        ]);

        // Cria o perfil de freelancer associado ao novo usuário
        PerfilFreelancer::create([
            'user_id' => $user->id,
        ]);

        event(new Registered($user));

        Auth::login($user);

        // --- SOLUÇÃO DEFINITIVA: Recarregar o utilizador da base de dados ---
        Auth::user()->refresh();
        // --- FIM DA SOLUÇÃO ---

        return redirect(RouteServiceProvider::HOME())->with('success', 'Cadastro de freelancer realizado com sucesso! Complete seu perfil.');
    }
}
