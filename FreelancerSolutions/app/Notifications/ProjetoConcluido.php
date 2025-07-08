<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Projeto;

class ProjetoConcluido extends Notification
{
    use Queueable;

    public $projeto;
    public $notifiableUserType; // 'cliente' or 'freelancer'

    /**
     * Create a new notification instance.
     */
    public function __construct(Projeto $projeto, string $notifiableUserType)
    {
        $this->projeto = $projeto;
        $this->notifiableUserType = $notifiableUserType;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $message = '';
        if ($this->notifiableUserType === 'cliente') {
            $message = 'Seu projeto "' . $this->projeto->titulo . '" foi marcado como concluído pelo freelancer. Por favor, revise e finalize.';
        } elseif ($this->notifiableUserType === 'freelancer') {
            $message = 'O projeto "' . $this->projeto->titulo . '" foi finalizado pelo cliente. Agora você pode avaliar o cliente.';
        }

        return [
            'projeto_id' => $this->projeto->id,
            'projeto_titulo' => $this->projeto->titulo,
            'message' => $message,
            'link' => route('projetos.show', $this->projeto->id),
        ];
    }
}
