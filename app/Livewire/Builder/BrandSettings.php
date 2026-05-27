<?php

namespace App\Livewire\Builder;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Tenant;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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
            try {
                // 1. Generate a clean filename
                $filename = 'logo_' . $tenant->id . '_' . time() . '.' . $this->logo->getClientOriginalExtension();
                $path = 'logos/' . $filename;

                // 2. Determine destination (S3/R2 is mandatory if configured)
                $s3Configured = !empty(config('filesystems.disks.s3.key'));
                $disk = $s3Configured ? 's3' : 'public';

                // 3. Upload using stream to bypass local filesystem limitations
                Storage::disk($disk)->put($path, fopen($this->logo->getRealPath(), 'r+'), 'public');

                // 4. Construct Absolute Cloudflare URL
                if ($s3Configured) {
                    $baseUrl = rtrim(config('filesystems.disks.s3.url'), '/');
                    $config['logo_url'] = $baseUrl . '/' . $path;
                } else {
                    $config['logo_url'] = Storage::disk('public')->url($path);
                }

                $this->current_logo_url = $config['logo_url'];
                Log::info("Logo transfer successful to {$disk}: " . $config['logo_url']);
            } catch (\Exception $e) {
                Log::error("CRITICAL ERROR UPLOADING LOGO: " . $e->getMessage());
                session()->flash('error', 'Error técnico: ' . $e->getMessage());
                return;
            }
        }

        // Save other settings
        $config['primary_color'] = $this->primary_color;
        $config['secondary_color'] = $this->secondary_color;
        $config['font_family'] = $this->font_family;
        $config['theme_mode'] = $this->theme_mode;

        $tenant->update([
            'name' => $this->company_name,
            'theme_config_json' => $config
        ]);

        session()->flash('message', '¡Configuración guardada exitosamente!');
        $this->reset('logo');
    }

    public function render()
    {
        return view('livewire.builder.brand-settings')->layout('components.layouts.app');
    }
}
