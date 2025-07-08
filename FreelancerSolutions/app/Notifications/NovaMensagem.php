<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Mensagem;

class NovaMensagem extends Notification
{
    use Queueable;

    public $mensagem;

    /**
     * Create a new notification instance.
     */
    public function __construct(Mensagem $mensagem)
    {
        $this->mensagem = $mensagem;
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
        return [
            'projeto_id' => $this->mensagem->projeto->id,
            'projeto_titulo' => $this->mensagem->projeto->titulo,
            'remetente_nome' => $this->mensagem->remetente->name,
            'message' => 'Você recebeu uma nova mensagem de ' . $this->mensagem->remetente->name . ' no projeto "' . $this->mensagem->projeto->titulo . '".',
            'link' => route('mensagens.index', $this->mensagem->projeto->id),
        ];
    }
}
