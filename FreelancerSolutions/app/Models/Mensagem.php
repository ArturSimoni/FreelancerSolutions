<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mensagem extends Model
{
    use HasFactory;

    protected $fillable = [
        'remetente_id',
        'destinatario_id',
        'projeto_id',
        'conteudo',
        'lida_em',
    ];

    protected $casts = [
        'lida_em' => 'datetime',
    ];


    public function remetente()
    {
        return $this->belongsTo(User::class, 'remetente_id');
    }

    public function destinatario()
    {
        return $this->belongsTo(User::class, 'destinatario_id');
    }

    public function projeto()
    {
        return $this->belongsTo(Projeto::class);
    }
}
