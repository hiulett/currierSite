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
    public $air_address;
    public $air_city;
    public $air_state;
    public $air_zip_code;
    public $air_phone;

    // Box Number Settings - Maritime
    public $box_number_prefix_maritime;
    public $box_number_template_maritime;
    public $maritime_address;
    public $maritime_city;
    public $maritime_state;
    public $maritime_zip_code;
    public $maritime_phone;

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
        $this->air_address = $settings['air_address'] ?? '2610 NW 89th CT';
        $this->air_city = $settings['air_city'] ?? 'Doral';
        $this->air_state = $settings['air_state'] ?? 'Florida';
        $this->air_zip_code = $settings['air_zip_code'] ?? '33172-1615';
        $this->air_phone = $settings['air_phone'] ?? '+1 (305) 848-1127';

        $this->box_number_prefix_maritime = $settings['box_number_prefix_maritime'] ?? 'SEA';
        $this->box_number_template_maritime = $settings['box_number_template_maritime'] ?? '{PREFIX}M{ID} {NAME}';
        $this->maritime_address = $settings['maritime_address'] ?? '2610 NW 89th CT';
        $this->maritime_city = $settings['maritime_city'] ?? 'Doral';
        $this->maritime_state = $settings['maritime_state'] ?? 'Florida';
        $this->maritime_zip_code = $settings['maritime_zip_code'] ?? '33172-1615';
        $this->maritime_phone = $settings['maritime_phone'] ?? '+1 (305) 848-1127';

        $this->box_number_counter = $settings['box_number_counter'] ?? 1000;

        $this->service_air_enabled = $settings['service_air_enabled'] ?? true;
        $this->service_maritime_enabled = $settings['service_maritime_enabled'] ?? true;
    }

    public function updatedServiceAirEnabled($value)
    {
        $tenant = Tenant::find(session('tenant_id'));
        $settings = $tenant->settings_json ?? [];
        $settings['service_air_enabled'] = (bool) $value;
        $tenant->update(['settings_json' => $settings]);
    }

    public function updatedServiceMaritimeEnabled($value)
    {
        $tenant = Tenant::find(session('tenant_id'));
        $settings = $tenant->settings_json ?? [];
        $settings['service_maritime_enabled'] = (bool) $value;
        $tenant->update(['settings_json' => $settings]);
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

        // Ensure toggles are saved here too
        $settings['service_air_enabled'] = (bool) $this->service_air_enabled;
        $settings['service_maritime_enabled'] = (bool) $this->service_maritime_enabled;

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
            'air_address' => 'required|string',
            'air_city' => 'required|string',
            'air_state' => 'required|string',
            'air_zip_code' => 'required|string',
            'air_phone' => 'required|string',
        ]);

        $tenant = Tenant::find(session('tenant_id'));
        $settings = $tenant->settings_json ?? [];

        $settings['box_number_prefix_air'] = strtoupper($this->box_number_prefix_air);
        $settings['box_number_template_air'] = $this->box_number_template_air;
        $settings['service_air_enabled'] = (bool) $this->service_air_enabled;
        $settings['air_address'] = $this->air_address;
        $settings['air_city'] = $this->air_city;
        $settings['air_state'] = $this->air_state;
        $settings['air_zip_code'] = $this->air_zip_code;
        $settings['air_phone'] = $this->air_phone;

        $tenant->update(['settings_json' => $settings]);
        session()->flash('message', 'Configuración de servicio aéreo actualizada.');
    }

    public function saveMaritime()
    {
        $this->validate([
            'box_number_prefix_maritime' => 'required|string|max:10',
            'box_number_template_maritime' => 'required|string',
            'maritime_address' => 'required|string',
            'maritime_city' => 'required|string',
            'maritime_state' => 'required|string',
            'maritime_zip_code' => 'required|string',
            'maritime_phone' => 'required|string',
        ]);

        $tenant = Tenant::find(session('tenant_id'));
        $settings = $tenant->settings_json ?? [];

        $settings['box_number_prefix_maritime'] = strtoupper($this->box_number_prefix_maritime);
        $settings['box_number_template_maritime'] = $this->box_number_template_maritime;
        $settings['service_maritime_enabled'] = (bool) $this->service_maritime_enabled;
        $settings['maritime_address'] = $this->maritime_address;
        $settings['maritime_city'] = $this->maritime_city;
        $settings['maritime_state'] = $this->maritime_state;
        $settings['maritime_zip_code'] = $this->maritime_zip_code;
        $settings['maritime_phone'] = $this->maritime_phone;

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
