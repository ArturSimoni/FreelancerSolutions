<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Candidatura;

class NewCandidatura extends Notification
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
        return ['database']; // Armazena no banco de dados
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
            'freelancer_id' => $this->candidatura->freelancer->id,
            'freelancer_nome' => $this->candidatura->freelancer->name,
            'message' => 'Um novo freelancer, ' . $this->candidatura->freelancer->name . ', se candidatou ao seu projeto "' . $this->candidatura->projeto->titulo . '".',
            'link' => route('projetos.show', $this->candidatura->projeto->id),
        ];
    }
}
