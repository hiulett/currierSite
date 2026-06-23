<?php

namespace App\Notifications;

use App\Models\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TemporaryPasswordNotification extends Notification
{


    public $password;
    public $name;
    public $tenant;

    public function __construct($password, $name, Tenant $tenant = null)
    {
        $this->password = $password;
        $this->name = $name;
        $this->tenant = $tenant;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        if ($this->tenant) {
            $this->tenant->setMailConfig();
        }
        $tenantName = $this->tenant?->name ?? config('app.name');

        return (new MailMessage)
            ->subject('Tu nueva contraseña de acceso - ' . $tenantName)
            ->greeting('¡Hola, ' . $this->name . '!')
            ->line('Hemos generado una nueva contraseña temporal para tu cuenta en nuestro portal logístico.')
            ->line('Tus credenciales de acceso son:')
            ->line('**Usuario:** ' . $notifiable->email)
            ->line('**Contraseña:** ' . $this->password)
            ->action('Entrar al Portal', url('/login'))
            ->line('Te recomendamos cambiar esta contraseña una vez que hayas ingresado por primera vez.')
            ->line('¡Gracias por confiar en ' . $tenantName . '!');
    }
}

