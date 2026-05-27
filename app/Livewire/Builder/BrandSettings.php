<?php

namespace App\Livewire\Builder;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Tenant;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class BrandSettings extends Component
{
    use WithFileUploads;

    public $primary_color;
    public $secondary_color;
    public $font_family;
    public $company_name;
    public $theme_mode;
    public $logo;
    public $current_logo_url;

    public function mount()
    {
        $tenant = $this->getTenant();
        $this->loadData($tenant);
    }

    protected function getTenant()
    {
        // Prioritize session then user relation then first record
        $id = session('tenant_id') ?? auth()->user()->tenant_id ?? Tenant::first()->id;
        return Tenant::findOrFail($id);
    }

    protected function loadData($tenant)
    {
        $this->company_name = $tenant->name;
        $config = $tenant->theme_config_json ?? [];

        $this->primary_color = $config['primary_color'] ?? ($config['primary'] ?? '#2563eb');
        $this->secondary_color = $config['secondary_color'] ?? '#0b5ed7';
        $this->font_family = $config['font_family'] ?? 'figtree';
        $this->theme_mode = $config['theme_mode'] ?? 'light';
        $this->current_logo_url = $config['logo_url'] ?? null;
    }

    public function save()
    {
        $this->validate([
            'company_name' => 'required|min:3',
            'logo' => 'nullable|image|max:2048',
        ]);

        $tenant = $this->getTenant();
        $config = $tenant->theme_config_json ?? [];

        // 1. Handle Logo Upload
        if ($this->logo) {
            try {
                $filename = 'logo_' . $tenant->id . '_' . time() . '.' . $this->logo->getClientOriginalExtension();
                $disk = !empty(config('filesystems.disks.s3.key')) ? 's3' : 'public';

                $path = $this->logo->storeAs('logos', $filename, $disk);

                if ($disk === 's3') {
                    $config['logo_url'] = rtrim(config('filesystems.disks.s3.url'), '/') . '/' . $path;
                } else {
                    $config['logo_url'] = Storage::disk('public')->url($path);
                }

                $this->current_logo_url = $config['logo_url'];
            } catch (\Exception $e) {
                Log::error("Upload failed: " . $e->getMessage());
                session()->flash('error', 'Error de red en la subida.');
                return;
            }
        } else {
            // Keep the previous logo if no new one is uploaded
            $config['logo_url'] = $this->current_logo_url;
        }

        // 2. Update Configuration Array
        $config['primary'] = $this->primary_color;
        $config['primary_color'] = $this->primary_color;
        $config['secondary_color'] = $this->secondary_color;
        $config['font_family'] = $this->font_family;
        $config['theme_mode'] = $this->theme_mode;

        // 3. ATOMIC SAVE: Assign directly to model and save
        $tenant->name = $this->company_name;
        $tenant->theme_config_json = $config;

        if ($tenant->save()) {
            session()->flash('message', 'Configuración guardada en la base de datos.');
            $this->reset('logo');
            $this->loadData($tenant); // Refresh state
        } else {
            session()->flash('error', 'La base de datos rechazó el guardado.');
        }
    }

    public function render()
    {
        return view('livewire.builder.brand-settings')->layout('components.layouts.app');
    }
}
