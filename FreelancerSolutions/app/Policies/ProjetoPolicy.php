<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Projeto;
use Illuminate\Auth\Access\Response;

class ProjetoPolicy
{
    public function before(User $user, string $ability)
    {
        if ($user->isAdministrador()) {
            return true;
        }
    }

    public function viewAny(User $user): bool
    {
        return $user->isCliente() || $user->isFreelancer() || $user->isAdministrador();
    }

    public function view(User $user, Projeto $projeto): bool
    {
        return $user->id === $projeto->cliente_id ||
               ($user->isFreelancer() && ($projeto->status === 'aberto' || ($projeto->freelancerAceito && $projeto->freelancerAceito->freelancer_id === $user->id)));
    }

    public function create(User $user): bool
    {
        return $user->isCliente();
    }

    /**
     * Determine whether the user can update the model.
     * Updated to handle specific status changes by freelancer.
     */
    public function update(User $user, Projeto $projeto): bool
    {
        // Cliente pode atualizar seu próprio projeto
        if ($user->id === $projeto->cliente_id) {
            return true;
        }

        // Freelancer aceito pode mudar o status para 'aguardando_aprovacao'
        if ($user->isFreelancer() &&
            $projeto->freelancerAceito &&
            $projeto->freelancerAceito->freelancer_id === $user->id &&
            $projeto->status === 'em_andamento') { // Apenas se o projeto estiver em andamento
            return true;
        }

        return false;
    }

    public function delete(User $user, Projeto $projeto): bool
    {
        return $user->id === $projeto->cliente_id;
    }

    public function restore(User $user, Projeto $projeto): bool
    {
        return $user->isAdministrador();
    }

    public function forceDelete(User $user, Projeto $projeto): bool
    {
        return $user->isAdministrador();
    }
}
