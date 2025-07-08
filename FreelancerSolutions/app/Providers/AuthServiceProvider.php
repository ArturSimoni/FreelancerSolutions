<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Projeto;
use App\Models\Mensagem; // Importe o modelo Mensagem
use App\Policies\UserPolicy;
use App\Policies\ProjetoPolicy;
use App\Policies\MensagemPolicy; // Importe a MensagemPolicy

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Projeto::class => ProjetoPolicy::class,
        Mensagem::class => MensagemPolicy::class, // Adicione esta linha
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('access-freelancer-dashboard', function ($user) {
            return method_exists($user, 'isFreelancer') && $user->isFreelancer();
        });
    }
}
