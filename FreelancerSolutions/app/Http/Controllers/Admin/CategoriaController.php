<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class CategoriaController extends Controller
{

    /**
     * Display a listing of the categories.
     */
    public function index()
    {
        $categorias = Categoria::latest()->get();
        return view('admin.categorias.index', compact('categorias'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        return view('admin.categorias.create');
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => ['required', 'string', 'max:255', 'unique:categorias,nome'],
            'descricao' => ['nullable', 'string', 'max:500'],
        ]);

        Categoria::create($request->all());

        return redirect()->route('admin.categorias.index')->with('success', 'Categoria criada com sucesso.');
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(Categoria $categoria)
    {
        return view('admin.categorias.edit', compact('categoria'));
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, Categoria $categoria)
    {
        $request->validate([
            'nome' => ['required', 'string', 'max:255', Rule::unique('categorias')->ignore($categoria->id)],
            'descricao' => ['nullable', 'string', 'max:500'],
        ]);

        $categoria->update($request->all());

        return redirect()->route('admin.categorias.index')->with('success', 'Categoria atualizada com sucesso.');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(Categoria $categoria)
    {
        $categoria->delete();
        return redirect()->route('admin.categorias.index')->with('success', 'Categoria excluída com sucesso.');
    }
}
