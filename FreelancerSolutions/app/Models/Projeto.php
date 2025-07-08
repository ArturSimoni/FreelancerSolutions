<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Projeto extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'titulo',
        'descricao',
        'orcamento',
        'prazo',
        'status',
    ];

    protected $casts = [
        'prazo' => 'date',
    ];

    public function cliente()
    {
        return $this->belongsTo(User::class, 'cliente_id');
    }

    public function candidaturas()
    {
        return $this->hasMany(Candidatura::class);
    }

    public function avaliacoes()
    {
        return $this->hasMany(AvaliacaoServico::class);
    }

    public function mensagens()
    {
        return $this->hasMany(Mensagem::class);
    }

    // Relacionamento muitos-para-muitos com Categoria
    public function categorias()
    {
        return $this->belongsToMany(Categoria::class, 'projeto_categoria');
    }

    // Método para obter o freelancer que foi aceito para este projeto
    public function freelancerAceito()
    {
        return $this->hasOne(Candidatura::class)->where('status', 'aceita')->with('freelancer');
    }
}
