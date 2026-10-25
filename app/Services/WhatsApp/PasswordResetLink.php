<?php

namespace App\Services\WhatsApp;

use App\Models\User;
use Illuminate\Support\Facades\Password;

/**
 * Genera el enlace de restablecimiento de contraseña que el administrador
 * comparte por WhatsApp Web. Nunca se envían contraseñas en texto plano.
 */
class PasswordResetLink
{
    /**
     * @return array{url: string, message: string, whatsapp: ?string}
     */
    public function build(User $user, ?string $phone): array
    {
        $token = Password::broker()->createToken($user);

        $url = route('password.reset', [
            'token' => $token,
            'email' => $user->getEmailForPasswordReset(),
        ]);

        $minutes = (int) (config('auth.passwords.'.config('auth.defaults.passwords').'.expire') ?: 60);

        $message = "Hola {$user->name},\n\n"
            ."Recibimos una solicitud para restablecer tu contraseña.\n\n"
            ."Abre este enlace para crear una nueva (válido {$minutes} minutos):\n{$url}\n\n"
            .'Si no lo solicitaste, ignora este mensaje.';

        return [
            'url' => $url,
            'message' => $message,
            'whatsapp' => WebChannel::url($phone, $message),
        ];
    }
}
