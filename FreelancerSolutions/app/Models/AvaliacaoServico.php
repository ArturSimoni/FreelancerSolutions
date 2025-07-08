<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvaliacaoServico extends Model
{
    use HasFactory;

    protected $fillable = [
        'avaliador_id',
        'avaliado_id',
        'projeto_id',
        'comentario',
        'nota',
        'tipo_avaliacao',
    ];

    public function avaliador()
    {
        return $this->belongsTo(User::class, 'avaliador_id');
    }

    public function avaliado()
    {
        return $this->belongsTo(User::class, 'avaliado_id');
    }

    public function projeto()
    {
        return $this->belongsTo(Projeto::class);
    }
}
