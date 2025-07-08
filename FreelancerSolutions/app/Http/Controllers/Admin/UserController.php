<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        // Apenas administradores podem acessar estas rotas
        $this->middleware('can:access-admin-dashboard');
    }

    /**
     * Display a listing of the users.
     */
    public function index()
    {
        $users = User::latest()->get();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'perfil' => ['required', 'string', Rule::in(['cliente', 'freelancer', 'administrador'])],
            // 'password' => ['nullable', 'confirmed', Rules\Password::defaults()], // Admin pode resetar senha
        ]);

        $user->update($request->only('name', 'email', 'perfil'));

        // if ($request->filled('password')) {
        //     $user->update(['password' => Hash::make($request->password)]);
        // }

        return redirect()->route('admin.users.index')->with('success', 'Usuário atualizado com sucesso.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Não permitir que o admin se exclua
        if (Auth::id() === $user->id) {
            return back()->with('error', 'Você não pode excluir sua própria conta de administrador.');
        }

        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Usuário excluído com sucesso.');
    }
}
