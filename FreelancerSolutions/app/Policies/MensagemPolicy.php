<?php

namespace App\Policies;

use App\Models\Mensagem;
use App\Models\User;
use App\Models\Projeto; // Importe o modelo Projeto
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Log;

class MensagemPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user !== null;
    }

    /**
     * Determine whether the user can view the model.
     * Esta política é para visualizar a interface de chat de um projeto específico.
     */
    public function view(User $user, Mensagem $mensagem = null, Projeto $projeto): bool
    {
        Log::debug('MensagemPolicy@view: User ID: ' . $user->id . ', Perfil: ' . $user->perfil);
        Log::debug('MensagemPolicy@view: Projeto ID: ' . $projeto->id . ', Cliente ID: ' . $projeto->cliente_id . ', Freelancer Aceito ID: ' . ($projeto->freelancer_aceito_id ?? 'Nulo'));

        // Um administrador sempre pode ver.
        if ($user->isAdministrador()) {
            Log::debug('MensagemPolicy@view: Autorizado como Administrador.');
            return true;
        }

        // O cliente do projeto pode ver.
        if ($user->id === $projeto->cliente_id) {
            Log::debug('MensagemPolicy@view: Autorizado como Cliente do Projeto.');
            return true;
        }

        // O freelancer aceite no projeto pode ver.
        if ($projeto->freelancer_aceito_id && $user->id === $projeto->freelancer_aceito_id) {
            Log::debug('MensagemPolicy@view: Autorizado como Freelancer Aceito do Projeto.');
            return true;
        }

        // Permite que o freelancer veja o chat se ele tiver uma candidatura
        if ($user->isFreelancer() && $projeto->candidaturas()->where('freelancer_id', $user->id)->exists()) {
            Log::debug('MensagemPolicy@view: Autorizado como Freelancer com candidatura.');
            return true;
        }

        Log::warning('MensagemPolicy@view: Acesso negado. User ID: ' . $user->id . ', Projeto ID: ' . $projeto->id);
        return false;
    }

    /**
     * Determine whether the user can create models (send messages).
     */
    public function create(User $user, Projeto $projeto): bool
    {
        Log::debug('MensagemPolicy@create: Verificando permissão para User ID: ' . $user->id . ' no Projeto ID: ' . $projeto->id);

        // 1. Administradores sempre podem enviar mensagens.
        if ($user->isAdministrador()) {
            Log::debug('Autorizado: Usuário é Administrador.');
            return true;
        }

        // 2. O cliente do projeto sempre pode enviar mensagens.
        if ($user->id === $projeto->cliente_id) {
            Log::debug('Autorizado: Usuário é o Cliente do Projeto.');
            return true;
        }

        // 3. O freelancer ACEITO para o projeto pode enviar mensagens.
        if ($projeto->freelancer_aceito_id && $user->id === $projeto->freelancer_aceito_id) {
            Log::debug('Autorizado: Usuário é o Freelancer Aceito.');
            return true;
        }

        // 4. (CORRIGIDO) Um freelancer que fez candidatura para o projeto pode enviar mensagens.
        if ($user->isFreelancer() && $projeto->candidaturas()->where('freelancer_id', '==', $user->id)->exists()) {
            Log::debug('Autorizado: Usuário é um Freelancer com candidatura no projeto.');
            return true;
        }

        Log::warning('Acesso NEGADO para criar mensagem. User ID: ' . $user->id . ' no Projeto ID: ' . $projeto->id);
        return false; // Se nenhuma das condições acima for atendida, nega o acesso.
    }

    /**
     * Determine whether the user can update the model (e.g., mark as read).
     */
    public function update(User $user, Mensagem $mensagem): bool
    {
        if ($user->isAdministrador()) {
            return true;
        }
        return $user->id === $mensagem->destinatario_id || $user->id === $mensagem->remetente_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Mensagem $mensagem): bool
    {
        if ($user->isAdministrador()) {
            return true;
        }
        return $user->id === $mensagem->remetente_id;
    }
}
