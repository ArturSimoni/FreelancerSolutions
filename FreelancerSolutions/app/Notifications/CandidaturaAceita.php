<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Candidatura;

class CandidaturaAceita extends Notification
{
    use Queueable;

    public $candidatura;

    /**
     * Create a new notification instance.
     */
    public function __construct(Candidatura $candidatura)
    {
        $this->candidatura = $candidatura;
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
            'projeto_id' => $this->candidatura->projeto->id,
            'projeto_titulo' => $this->candidatura->projeto->titulo,
            'cliente_id' => $this->candidatura->projeto->cliente->id,
            'cliente_nome' => $this->candidatura->projeto->cliente->name,
            'message' => 'Sua candidatura ao projeto "' . $this->candidatura->projeto->titulo . '" foi aceita pelo cliente ' . $this->candidatura->projeto->cliente->name . '! O projeto agora está em andamento.',
            'link' => route('projetos.show', $this->candidatura->projeto->id),
        ];
    }
}
