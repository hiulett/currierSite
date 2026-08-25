<?php

namespace App\Livewire\SuperAdmin;

use App\Models\AppSetting;
use Livewire\Component;

class CoreSettings extends Component
{
    public $platform_name;

    public $support_email;

    public $system_status;

    public $maintenance_message;

    public $base_currency;

    public $timezone;

    public $whatsapp_pdf_enabled = false;

    public function mount()
    {
        $this->platform_name = AppSetting::get('platform_name', config('app.name', 'Sistema Logístico'));
        $this->support_email = AppSetting::get('support_email', env('SUPPORT_EMAIL', 'soporte@'.request()->getHost()));
        $this->system_status = AppSetting::get('system_status', 'online');
        $this->maintenance_message = AppSetting::get('maintenance_message', 'Estamos realizando mejoras técnicas...');
        $this->base_currency = AppSetting::get('base_currency', 'USD');
        $this->timezone = AppSetting::get('timezone', 'America/Panama');
        $this->whatsapp_pdf_enabled = (bool) AppSetting::get('whatsapp_pdf_enabled', false);
    }

    public function save()
    {
        AppSetting::set('platform_name', $this->platform_name);
        AppSetting::set('support_email', $this->support_email);
        AppSetting::set('system_status', $this->system_status);
        AppSetting::set('maintenance_message', $this->maintenance_message);
        AppSetting::set('base_currency', $this->base_currency);
        AppSetting::set('timezone', $this->timezone);
        AppSetting::set('whatsapp_pdf_enabled', $this->whatsapp_pdf_enabled ? '1' : '0');

        session()->flash('message', 'Ajustes del núcleo actualizados correctamente.');
    }

    public function render()
    {
        return view('livewire.super-admin.core-settings')->layout('components.super-admin-layout');
    }
}
