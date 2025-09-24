<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NoteDeletedNotification extends Notification
{
    use Queueable;

    public $noteTitle;
    public $reason;

    public function __construct($noteTitle, $reason)
    {
        $this->noteTitle = $noteTitle;
        $this->reason = $reason;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('Tu nota ha sido eliminada por un administrador.')
                    ->line('Título de la nota: ' . $this->noteTitle)
                    ->line('Razón: ' . $this->reason)
                    ->action('Ver tus notas', url('/notes'))
                    ->line('¡Gracias por usar nuestra aplicación!');
    }
}