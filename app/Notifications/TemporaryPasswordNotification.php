<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TemporaryPasswordNotification extends Notification
{
    use Queueable;

    public $password;
    public $name;

    public function __construct($password, $name)
    {
        $this->password = $password;
        $this->name = $name;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Tu nueva contraseña de acceso - ' . config('app.name'))
            ->greeting('¡Hola, ' . $this->name . '!')
            ->line('Hemos generado una nueva contraseña temporal para tu cuenta en nuestro portal logístico.')
            ->line('Tus credenciales de acceso son:')
            ->line('**Usuario:** ' . $notifiable->email)
            ->line('**Contraseña:** ' . $this->password)
            ->action('Entrar al Portal', url('/login'))
            ->line('Te recomendamos cambiar esta contraseña una vez que hayas ingresado por primera vez.')
            ->line('¡Gracias por confiar en nosotros!');
    }
}
