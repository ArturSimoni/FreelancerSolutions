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

    /**
     * Usuário que enviou a mensagem.
     */
    public function remetente()
    {
        return $this->belongsTo(User::class, 'remetente_id');
    }

    /**
     * Usuário que recebeu a mensagem.
     */
    public function destinatario()
    {
        return $this->belongsTo(User::class, 'destinatario_id');
    }

    /**
     * Projeto relacionado à mensagem.
     */
    public function projeto()
    {
        return $this->belongsTo(Projeto::class);
    }
}
