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

        $settings['mail_host'] = $this->mail_host;
        $settings['mail_port'] = $this->mail_port;
        $settings['mail_username'] = $this->mail_username;
        $settings['mail_password'] = $this->mail_password;
        $settings['mail_encryption'] = $this->mail_encryption;
        $settings['mail_from_address'] = $this->mail_from_address;
        $settings['mail_from_name'] = $this->mail_from_name;

        $tenant->update(['settings_json' => $settings]);

        session()->flash('message', 'Configuración de correo actualizada correctamente.');
    }

    public function render()
    {
        return view('livewire.builder.mail-settings')->layout('components.layouts.app');
    }
}
