<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Log;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->isAdministrador()) {
            return true;
        }

        return null; // Deixa as outras regras da policy decidirem para não-admins
    }

    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        return $user->isAdministrador() || $user->id === $model->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        return $user->isAdministrador() || $user->id === $model->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        return $user->isAdministrador();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return $user->isAdministrador();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return $user->isAdministrador();
    }

    public function accessAdminDashboard(User $user): bool
    {
        return $user->isAdministrador();
    }

    public function accessFreelancerDashboard(User $user): bool
    {
        Log::info("Avaliando Policy 'accessFreelancerDashboard' para o utilizador ID: " . ($user->id ?? 'NULO'));
        Log::info("Perfil do utilizador (Policy): " . ($user->perfil ?? 'NULO'));
        Log::info("Resultado de isFreelancer(): " . ($user->isFreelancer() ? 'Sim' : 'Não'));

        return $user->isFreelancer();

    }
}
