<?php

namespace App\Livewire\Builder;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Tenant;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class BrandSettings extends Component
{
    use WithFileUploads;

    public $primary_color;
    public $secondary_color;
    public $font_family;
    public $company_name;
    public $theme_mode; // light, dark, slate, oceanic
    public $logo;
    public $current_logo_url;

    public function mount()
    {
        $tenant = Tenant::find(session('tenant_id'));
        $this->company_name = $tenant->name;

        $config = $tenant->theme_config_json ?? [];
        $this->primary_color = $config['primary_color'] ?? '#0d6efd';
        $this->secondary_color = $config['secondary_color'] ?? '#0b5ed7';
        $this->font_family = $config['font_family'] ?? 'figtree';
        $this->theme_mode = $config['theme_mode'] ?? 'light';
        $this->current_logo_url = $config['logo_url'] ?? null;
    }

    public function save()
    {
        $tenant = Tenant::find(session('tenant_id'));
        $config = $tenant->theme_config_json ?? [];

        if ($this->logo) {
            // Check if S3 is configured, otherwise use public
            $disk = config('filesystems.disks.s3.key') ? 's3' : 'public';

            try {
                // Using putFile to avoid internal 'move' issues with some S3 providers
                $path = Storage::disk($disk)->putFile('logos', $this->logo);
                $config['logo_url'] = Storage::disk($disk)->url($path);
                $this->current_logo_url = $config['logo_url'];
            } catch (\Exception $e) {
                Log::error("Logo upload failed: " . $e->getMessage());
                session()->flash('error', 'Error al subir el logo: ' . $e->getMessage());
                return;
            }
        }

        $config['primary_color'] = $this->primary_color;
        $config['secondary_color'] = $this->secondary_color;
        $config['font_family'] = $this->font_family;
        $config['theme_mode'] = $this->theme_mode;

        $tenant->update([
            'name' => $this->company_name,
            'theme_config_json' => $config
        ]);

        session()->flash('message', 'Configuración de marca actualizada.');
    }

    public function render()
    {
        return view('livewire.builder.brand-settings')->layout('components.layouts.app');
    }
}
