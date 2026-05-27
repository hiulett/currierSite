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
        $this->company_name = $tenant->name;

        $config = $tenant->theme_config_json ?? [];
        $this->primary_color = $config['primary_color'] ?? '#0d6efd';
        $this->secondary_color = $config['secondary_color'] ?? '#0b5ed7';
        $this->font_family = $config['font_family'] ?? 'figtree';
        $this->theme_mode = $config['theme_mode'] ?? 'light';
        $this->current_logo_url = $config['logo_url'] ?? null;
    }

    protected function getTenant()
    {
        $id = session('tenant_id') ?? auth()->user()->tenant_id;
        return Tenant::find($id) ?? Tenant::first();
    }

    public function save()
    {
        $this->validate([
            'company_name' => 'required|string|max:100',
            'logo' => 'nullable|image|max:2048', // 2MB Max
        ]);

        $tenant = $this->getTenant();

        // Load current config to avoid losing other keys
        $config = $tenant->theme_config_json ?? [];

        if ($this->logo) {
            try {
                $filename = 'logo_' . $tenant->id . '_' . time() . '.' . $this->logo->getClientOriginalExtension();
                $path = 'logos/' . $filename;

                $disk = !empty(config('filesystems.disks.s3.key')) ? 's3' : 'public';

                // Use putFile for better S3 compatibility
                $storedPath = Storage::disk($disk)->putFileAs('logos', $this->logo, $filename);

                if ($disk === 's3') {
                    $baseUrl = rtrim(config('filesystems.disks.s3.url'), '/');
                    $config['logo_url'] = $baseUrl . '/' . $storedPath;
                } else {
                    $config['logo_url'] = Storage::disk('public')->url($storedPath);
                }

                $this->current_logo_url = $config['logo_url'];
            } catch (\Exception $e) {
                Log::error("Logo Upload Failure: " . $e->getMessage());
                session()->flash('error', 'Error al subir imagen: ' . $e->getMessage());
                return;
            }
        }

        // Update colors and theme
        $config['primary_color'] = $this->primary_color;
        $config['secondary_color'] = $this->secondary_color;
        $config['font_family'] = $this->font_family;
        $config['theme_mode'] = $this->theme_mode;

        // Force the primary color for the system theme engine
        $config['primary'] = $this->primary_color;

        // CRITICAL: Explicit model assignment for JSON columns
        $tenant->name = $this->company_name;
        $tenant->theme_config_json = $config;

        if ($tenant->save()) {
            Log::info("Tenant ID {$tenant->id} updated. New Logo URL: " . ($config['logo_url'] ?? 'None'));
            session()->flash('message', '¡Configuración guardada correctamente!');
        } else {
            Log::error("Database failed to save tenant settings for ID: " . $tenant->id);
            session()->flash('error', 'Error al guardar en la base de datos.');
        }

        $this->reset('logo');
    }

    public function render()
    {
        return view('livewire.builder.brand-settings')->layout('components.layouts.app');
    }
}
