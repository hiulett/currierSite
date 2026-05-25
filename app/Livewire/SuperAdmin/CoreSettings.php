<?php

namespace App\Livewire\SuperAdmin;

use Livewire\Component;
use App\Models\AppSetting;

class CoreSettings extends Component
{
    public $platform_name;
    public $support_email;
    public $system_status;
    public $maintenance_message;
    public $base_currency;
    public $timezone;

    public function mount()
    {
        $this->platform_name = AppSetting::get('platform_name', 'LogiSaaS');
        $this->support_email = AppSetting::get('support_email', 'support@logisaas.com');
        $this->system_status = AppSetting::get('system_status', 'online');
        $this->maintenance_message = AppSetting::get('maintenance_message', 'Estamos realizando mejoras técnicas...');
        $this->base_currency = AppSetting::get('base_currency', 'USD');
        $this->timezone = AppSetting::get('timezone', 'America/Panama');
    }

    public function save()
    {
        AppSetting::set('platform_name', $this->platform_name);
        AppSetting::set('support_email', $this->support_email);
        AppSetting::set('system_status', $this->system_status);
        AppSetting::set('maintenance_message', $this->maintenance_message);
        AppSetting::set('base_currency', $this->base_currency);
        AppSetting::set('timezone', $this->timezone);

        session()->flash('message', 'Ajustes del núcleo actualizados correctamente.');
    }

    public function render()
    {
        return view('livewire.super-admin.core-settings')->layout('components.super-admin-layout');
    }
}
