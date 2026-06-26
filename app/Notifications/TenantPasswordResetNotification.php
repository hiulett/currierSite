<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use App\Models\Tenant;
use Illuminate\Support\Facades\Lang;

class TenantPasswordResetNotification extends ResetPasswordNotification
{
    use Queueable;

    public $tenant;

    /**
     * Create a new notification instance.
     */
    public function __construct($token, Tenant $tenant = null)
    {
        parent::__construct($token);
        $this->tenant = $tenant;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        if ($this->tenant) {
            $this->tenant->setMailConfig();
        }
        
        $tenantName = $this->tenant?->name ?? config('app.name');

        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $this->token);
        }

        return (new MailMessage)
            ->subject(Lang::get('Notificación de restablecimiento de contraseña') . ' - ' . $tenantName)
            ->line(Lang::get('Estás recibiendo este correo porque recibimos una solicitud de restablecimiento de contraseña para tu cuenta.'))
            ->action(Lang::get('Restablecer Contraseña'), url(route('password.reset', [
                'token' => $this->token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false)))
            ->line(Lang::get('Este enlace de restablecimiento de contraseña caducará en :count minutos.', ['count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire')]))
            ->line(Lang::get('Si no solicitaste un restablecimiento de contraseña, no se requiere ninguna otra acción.'))
            ->salutation('Saludos, ' . $tenantName);
    }
}
