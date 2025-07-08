<?php

namespace App\Policies;

use App\Models\Candidatura;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CandidaturaPolicy
{
    /**
     * Determine whether the user can accept the candidature.
     */
    public function aceitar(User $user, Candidatura $candidatura): bool
    {
        // Apenas o cliente que é dono do projeto pode aceitar a candidatura.
        // O cliente_id do projeto deve ser igual ao ID do utilizador logado.
        return $user->id === $candidatura->projeto->cliente_id;
    }

    /**
     * Determine whether the user can reject the candidature.
     */
    public function recusar(User $user, Candidatura $candidatura): bool
    {
        // Apenas o cliente que é dono do projeto pode recusar a candidatura.
        return $user->id === $candidatura->projeto->cliente_id;
    }

    // Você pode adicionar outras políticas aqui, se necessário.
    // Por exemplo, para um freelancer ver sua própria candidatura:
    public function view(User $user, Candidatura $candidatura): bool
    {
        return $user->id === $candidatura->freelancer_id || $user->id === $candidatura->projeto->cliente_id || $user->isAdministrador();
    }
}
