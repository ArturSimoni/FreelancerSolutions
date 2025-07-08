<?php

namespace App\Policies;

use App\Models\Mensagem;
use App\Models\User;
use App\Models\Projeto; // Importe o modelo Projeto
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Log; // Adicione esta linha para usar Log

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

        Log::warning('MensagemPolicy@view: Acesso negado. User ID: ' . $user->id . ', Projeto ID: ' . $projeto->id . '. Razão: Não é administrador, cliente ou freelancer aceite.');
        return false; // Nenhuma das condições acima foi satisfeita
    }

    /**
     * Determine whether the user can create models (send messages).
     */
    public function create(User $user, Projeto $projeto): bool
    {
        Log::debug('MensagemPolicy@create: User ID: ' . $user->id . ', Perfil: ' . $user->perfil);
        Log::debug('MensagemPolicy@create: Projeto ID: ' . $projeto->id . ', Cliente ID: ' . $projeto->cliente_id . ', Freelancer Aceito ID: ' . ($projeto->freelancer_aceito_id ?? 'Nulo'));

        // Um administrador sempre pode criar (enviar mensagens).
        if ($user->isAdministrador()) {
            Log::debug('MensagemPolicy@create: Autorizado como Administrador.');
            return true;
        }

        // Apenas o cliente do projeto OU o freelancer aceite no projeto pode enviar mensagens.
        // E o projeto deve ter um freelancer aceite para que o chat seja ativo.
        if ($projeto->freelancer_aceito_id) { // Verifica se há um freelancer aceite
            if ($user->id === $projeto->cliente_id) {
                Log::debug('MensagemPolicy@create: Autorizado como Cliente do Projeto.');
                return true;
            }
            if ($user->id === $projeto->freelancer_aceito_id) {
                Log::debug('MensagemPolicy@create: Autorizado como Freelancer Aceito do Projeto.');
                return true;
            }
        }

        Log::warning('MensagemPolicy@create: Acesso negado. User ID: ' . $user->id . ', Projeto ID: ' . $projeto->id . '. Razão: Não é administrador, cliente ou freelancer aceite, ou projeto sem freelancer aceite.');
        return false; // Nenhuma das condições acima foi satisfeita
    }

    /**
     * Determine whether the user can update the model (e.g., mark as read).
     */
    public function update(User $user, Mensagem $mensagem): bool
    {
        // Um administrador pode atualizar qualquer mensagem.
        if ($user->isAdministrador()) {
            return true;
        }

        // Apenas o destinatário da mensagem pode marcá-la como lida.
        // Ou o remetente pode editar (se essa funcionalidade for permitida).
        return $user->id === $mensagem->destinatario_id || $user->id === $mensagem->remetente_id;
    }

    /**
     * Determine whether the user can mark the message as read.
     */
    public function markAsRead(User $user, Mensagem $mensagem): bool
    {
        // Um administrador pode marcar qualquer mensagem como lida.
        if ($user->isAdministrador()) {
            return true;
        }

        // Apenas o destinatário da mensagem pode marcá-la como lida.
        return $user->id === $mensagem->destinatario_id;
    }

    /**
     * Determine whether the user can edit the message content.
     * (Considerar cuidadosamente se esta funcionalidade é desejada em um chat).
     */
    public function edit(User $user, Mensagem $mensagem): bool
    {
        // Um administrador pode editar qualquer mensagem.
        if ($user->isAdministrador()) {
            return true;
        }

        // Apenas o remetente pode editar sua própria mensagem,
        // e talvez apenas por um curto período após o envio.
        // Exemplo: Permitir edição por 5 minutos após o envio.
        return $user->id === $mensagem->remetente_id && $mensagem->created_at->diffInMinutes(now()) < 5;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Mensagem $mensagem): bool
    {
        // Um administrador pode deletar qualquer mensagem.
        if ($user->isAdministrador()) {
            return true;
        }

        // Apenas o remetente pode deletar sua própria mensagem.
        // Ou talvez o cliente/freelancer aceite (se a regra de negócio permitir).
        return $user->id === $mensagem->remetente_id;
    }

    /**
     * Determine whether the user can reassign the message (e.g., to another project or user).
     * Esta é uma funcionalidade mais avançada e geralmente restrita.
     */
    public function reassign(User $user, Mensagem $mensagem): bool
    {
        // Apenas administradores podem reatribuir mensagens.
        return $user->isAdministrador();
    }

    /**
     * Determine whether the user can restore the model (if using soft deletes).
     */
    public function restore(User $user, Mensagem $mensagem): bool
    {
        // Apenas administradores podem restaurar mensagens.
        return $user->isAdministrador();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Mensagem $mensagem): bool
    {
        // Apenas administradores podem forçar a exclusão de mensagens.
        return $user->isAdministrador();
    }
}
