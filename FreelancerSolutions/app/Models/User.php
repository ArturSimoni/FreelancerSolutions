<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'perfil', // Certifique-se de que 'perfil' está aqui
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Relacionamentos:
    public function perfilFreelancer()
    {
        return $this->hasOne(PerfilFreelancer::class);
    }

    public function projetosPublicados()
    {
        return $this->hasMany(Projeto::class, 'cliente_id');
    }

    public function candidaturas()
    {
        return $this->hasMany(Candidatura::class, 'freelancer_id');
    }

    public function avaliacoesFeitas()
    {
        return $this->hasMany(AvaliacaoServico::class, 'avaliador_id');
    }

    public function avaliacoesRecebidas()
    {
        return $this->hasMany(AvaliacaoServico::class, 'avaliado_id');
    }

    public function mensagensEnviadas()
    {
        return $this->hasMany(Mensagem::class, 'remetente_id');
    }

    public function mensagensRecebidas()
    {
        return $this->hasMany(Mensagem::class, 'destinatario_id');
    }

    public function projetosAceitos()
    {
        return $this->hasManyThrough(
            Projeto::class,
            Candidatura::class,
            'freelancer_id',
            'id',
            'id',
            'projeto_id'
        )->where('candidaturas.status', 'aceita');
    }

    // Métodos auxiliares para verificar o perfil
    public function isCliente()
    {
        return $this->perfil === 'cliente';
    }

    public function isFreelancer()
    {
        return $this->perfil === 'freelancer';
    }

    public function isAdministrador()
    {
        return $this->perfil === 'administrador';
    }
}
