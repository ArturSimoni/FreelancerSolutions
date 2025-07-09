<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Habilidade extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nome',
        'descricao',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
    ];

    /**
     * Get the freelancer profiles that possess this skill.
     * Define a relação muitos-para-muitos com PerfilFreelancer.
     */
    public function perfilFreelancers()
    {
        return $this->belongsToMany(PerfilFreelancer::class, 'perfil_freelancer_habilidade', 'habilidade_id', 'perfil_freelancer_id');
    }
}
