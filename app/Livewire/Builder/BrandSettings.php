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
        $tenant = Tenant::find(session('tenant_id')) ?? Tenant::first();
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
        $tenant = Tenant::find(session('tenant_id')) ?? Tenant::first();
        $config = $tenant->theme_config_json ?? [];

        if ($this->logo) {
            // Priority 1: Cloudflare R2 (S3)
            // Priority 2: Public Local Disk
            $disk = !empty(config('filesystems.disks.s3.key')) ? 's3' : 'public';

            try {
                // Store file and get path
                $path = $this->logo->store('logos', $disk);

                // Get the URL based on the disk
                if ($disk === 's3') {
                    // Force the Cloudflare public URL if available
                    $baseUrl = rtrim(config('filesystems.disks.s3.url'), '/');
                    $config['logo_url'] = $baseUrl . '/' . $path;
                } else {
                    $config['logo_url'] = Storage::disk('public')->url($path);
                }

                $this->current_logo_url = $config['logo_url'];
                Log::info("Logo updated successfully on {$disk}: " . $config['logo_url']);
            } catch (\Exception $e) {
                Log::error("Critical Logo Upload Failure: " . $e->getMessage());
                session()->flash('error', 'Fallo técnico en la subida: ' . $e->getMessage());
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

        session()->flash('message', '¡Configuración e imagen guardadas con éxito!');
        $this->reset('logo'); // Important to clear the file input
    }

    public function render()
    {
        return view('livewire.builder.brand-settings')->layout('components.layouts.app');
    }
}
