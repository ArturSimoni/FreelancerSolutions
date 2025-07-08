<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerfilFreelancer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bio',
        'portifolio_url',
        'preco_hora',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relacionamento muitos-para-muitos com Habilidade
    public function habilidades()
    {
        return $this->belongsToMany(Habilidade::class, 'perfil_freelancer_habilidade');
    }
}
