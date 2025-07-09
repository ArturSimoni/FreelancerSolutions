<?php

namespace App\Policies;

use App\Models\Candidatura;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CandidaturaPolicy
{

    public function aceitar(User $user, Candidatura $candidatura): bool
    {
        return $user->id === $candidatura->projeto->cliente_id;
    }

    public function recusar(User $user, Candidatura $candidatura): bool
    {
        return $user->id === $candidatura->projeto->cliente_id;
    }

    public function view(User $user, Candidatura $candidatura): bool
    {
        return $user->id === $candidatura->freelancer_id || $user->id === $candidatura->projeto->cliente_id || $user->isAdministrador();
    }
}
