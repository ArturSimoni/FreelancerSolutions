<?php

namespace App\Providers;

use App\Models\Categoria;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Projeto;
use App\Models\Mensagem;
use App\Policies\UserPolicy;
use App\Policies\ProjetoPolicy;
use App\Policies\MensagemPolicy;
use App\Policies\CategoriaPolicy;

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
        Mensagem::class => MensagemPolicy::class,
        Categoria::class => CategoriaPolicy::class,
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

