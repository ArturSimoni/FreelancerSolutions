<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'descricao',
    ];

    // Relacionamento muitos-para-muitos com Projeto
    public function projetos()
    {
        return $this->belongsToMany(Projeto::class, 'projeto_categoria');
    }
}
