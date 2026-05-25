<?php

namespace App\Livewire\Builder;

use Livewire\Component;
use App\Models\Tenant;

class GeneralSettings extends Component
{
    public $currency;
    public $default_tax;
    public $default_rate;
    public $timezone;
    public $locale;

    // Box Number Settings - Air
    public $box_number_prefix_air;
    public $box_number_template_air;

    // Box Number Settings - Maritime
    public $box_number_prefix_maritime;
    public $box_number_template_maritime;

    public $box_number_counter;

    // Service Toggles
    public $service_air_enabled = true;
    public $service_maritime_enabled = true;

    public function mount()
    {
        $tenant = Tenant::find(session('tenant_id'));
        $settings = $tenant->settings_json ?? [];

        $this->currency = $settings['currency'] ?? 'USD';
        $this->default_tax = $settings['default_tax'] ?? 7;
        $this->default_rate = $settings['default_rate'] ?? 2.50;
        $this->timezone = $settings['timezone'] ?? 'UTC';
        $this->locale = $tenant->locale ?? 'es';

        $this->box_number_prefix_air = $settings['box_number_prefix_air'] ?? 'AIR';
        $this->box_number_template_air = $settings['box_number_template_air'] ?? '{PREFIX}{ID} {NAME}';

        $this->box_number_prefix_maritime = $settings['box_number_prefix_maritime'] ?? 'SEA';
        $this->box_number_template_maritime = $settings['box_number_template_maritime'] ?? '{PREFIX}M{ID} {NAME}';

        $this->box_number_counter = $settings['box_number_counter'] ?? 1000;

        $this->service_air_enabled = $settings['service_air_enabled'] ?? true;
        $this->service_maritime_enabled = $settings['service_maritime_enabled'] ?? true;
    }

    public function saveGeneral()
    {
        $this->validate([
            'currency' => 'required|string|max:3',
            'default_tax' => 'required|numeric|min:0',
            'default_rate' => 'required|numeric|min:0',
            'locale' => 'required|in:es,en',
        ]);

        $tenant = Tenant::find(session('tenant_id'));
        $settings = $tenant->settings_json ?? [];

        $settings['currency'] = strtoupper($this->currency);
        $settings['default_tax'] = $this->default_tax;
        $settings['default_rate'] = $this->default_rate;
        $settings['timezone'] = $this->timezone;

        $tenant->update([
            'settings_json' => $settings,
            'locale' => $this->locale
        ]);

        session()->flash('message', 'Configuraciones generales actualizadas correctamente.');
        return redirect()->route('builder.general');
    }

    public function saveAir()
    {
        $this->validate([
            'box_number_prefix_air' => 'required|string|max:10',
            'box_number_template_air' => 'required|string',
        ]);

        $tenant = Tenant::find(session('tenant_id'));
        $settings = $tenant->settings_json ?? [];

        $settings['box_number_prefix_air'] = strtoupper($this->box_number_prefix_air);
        $settings['box_number_template_air'] = $this->box_number_template_air;
        $settings['service_air_enabled'] = (bool) $this->service_air_enabled;

        $tenant->update(['settings_json' => $settings]);
        session()->flash('message', 'Configuración de servicio aéreo actualizada.');
    }

    public function saveMaritime()
    {
        $this->validate([
            'box_number_prefix_maritime' => 'required|string|max:10',
            'box_number_template_maritime' => 'required|string',
        ]);

        $tenant = Tenant::find(session('tenant_id'));
        $settings = $tenant->settings_json ?? [];

        $settings['box_number_prefix_maritime'] = strtoupper($this->box_number_prefix_maritime);
        $settings['box_number_template_maritime'] = $this->box_number_template_maritime;
        $settings['service_maritime_enabled'] = (bool) $this->service_maritime_enabled;

        $tenant->update(['settings_json' => $settings]);
        session()->flash('message', 'Configuración de servicio marítimo actualizada.');
    }

    public function saveCounter()
    {
        $this->validate([
            'box_number_counter' => 'required|integer|min:0',
        ]);

        $tenant = Tenant::find(session('tenant_id'));
        $settings = $tenant->settings_json ?? [];

        $settings['box_number_counter'] = (int) $this->box_number_counter;

        $tenant->update(['settings_json' => $settings]);
        session()->flash('message', 'Contador secuencial actualizado.');
    }

    public function render()
    {
        return view('livewire.builder.general-settings')->layout('components.layouts.app');
    }
}
