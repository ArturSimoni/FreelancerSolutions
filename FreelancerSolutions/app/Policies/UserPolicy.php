<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Log; // Adicione esta linha para usar Log

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Todos podem ver listagens de usuários (se necessário)
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        // Administrador pode ver qualquer perfil
        // Usuário pode ver seu próprio perfil
        return $user->isAdministrador() || $user->id === $model->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // Registro é público
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        // Administrador pode editar qualquer perfil
        // Usuário pode editar seu próprio perfil
        return $user->isAdministrador() || $user->id === $model->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        // Apenas administrador pode deletar usuários
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

    // Políticas específicas para Dashboards
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
