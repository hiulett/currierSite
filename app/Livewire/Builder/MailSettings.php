<?php

namespace App\Livewire\Builder;

use Livewire\Component;
use App\Models\Tenant;

class MailSettings extends Component
{
    public $mail_host;
    public $mail_port;
    public $mail_username;
    public $mail_password;
    public $mail_encryption;
    public $mail_from_address;
    public $mail_from_name;

    public function mount()
    {
        $tenant = Tenant::find(session('tenant_id'));
        $settings = $tenant->settings_json ?? [];

        $this->mail_host = $settings['mail_host'] ?? '';
        $this->mail_port = $settings['mail_port'] ?? '587';
        $this->mail_username = $settings['mail_username'] ?? '';
        $this->mail_password = $settings['mail_password'] ?? '';
        $this->mail_encryption = $settings['mail_encryption'] ?? 'tls';
        $this->mail_from_address = $settings['mail_from_address'] ?? '';
        $this->mail_from_name = $settings['mail_from_name'] ?? $tenant->name;
    }

    public function save()
    {
        $tenant = Tenant::find(session('tenant_id'));
        $settings = $tenant->settings_json ?? [];

        $settings['mail_host'] = trim($this->mail_host);
        $settings['mail_port'] = trim($this->mail_port);
        $settings['mail_username'] = trim($this->mail_username);
        $settings['mail_password'] = trim($this->mail_password);
        $settings['mail_encryption'] = trim($this->mail_encryption);
        $settings['mail_from_address'] = trim($this->mail_from_address);
        $settings['mail_from_name'] = trim($this->mail_from_name);

        $tenant->update(['settings_json' => $settings]);

        session()->flash('message', 'Configuración de correo actualizada correctamente.');
    }

    public function sendTestMail()
    {
        $this->save(); // Save first to ensure we use current values

        $tenant = Tenant::find(session('tenant_id'));

        try {
            // Force config reload for this request
            config([
                'mail.default' => 'smtp',
                'mail.mailers.smtp.host' => $this->mail_host,
                'mail.mailers.smtp.port' => $this->mail_port,
                'mail.mailers.smtp.username' => $this->mail_username,
                'mail.mailers.smtp.password' => $this->mail_password,
                'mail.mailers.smtp.encryption' => $this->mail_encryption,
                'mail.from.address' => $this->mail_from_address,
                'mail.from.name' => $this->mail_from_name,
            ]);

            \Illuminate\Support\Facades\Mail::raw("Este es un correo de prueba de LogiSaaS para validar tu configuración SMTP. Si recibiste esto, ¡todo está funcionando correctamente!", function ($message) {
                $message->to($this->mail_from_address)
                        ->subject('Prueba de Configuración de Correo - LogiSaaS');
            });

            session()->flash('message', '¡Éxito! Correo de prueba enviado a: ' . $this->mail_from_address);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Test Mail Error: ' . $e->getMessage());
            session()->flash('error', 'Error al enviar el correo: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.builder.mail-settings')->layout('components.layouts.app');
    }
}
