<?php

namespace App\Livewire\Builder;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Tenant;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

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
        $this->primary_color = $config['primary_color'] ?? ($config['primary'] ?? '#0d6efd');
        $this->secondary_color = $config['secondary_color'] ?? '#0b5ed7';
        $this->font_family = $config['font_family'] ?? 'figtree';
        $this->theme_mode = $config['theme_mode'] ?? 'light';
        $this->current_logo_url = $config['logo_url'] ?? null;
    }

    protected function getTenant()
    {
        // Get the real tenant ID from session or user account
        $id = session('tenant_id') ?? auth()->user()->tenant_id;

        // If still no ID, use the first one available
        if (!$id) {
            $first = Tenant::first();
            $id = $first->id;
            session(['tenant_id' => $id]);
        }

        return Tenant::find($id);
    }

    public function save()
    {
        $this->validate([
            'company_name' => 'required|string|max:100',
            'logo' => 'nullable|image|max:2048',
        ]);

        $tenant = $this->getTenant();
        $config = $tenant->theme_config_json ?? [];

        if ($this->logo) {
            try {
                $filename = 'logo_' . $tenant->id . '_' . time() . '.' . $this->logo->getClientOriginalExtension();
                $disk = !empty(config('filesystems.disks.s3.key')) ? 's3' : 'public';

                $storedPath = Storage::disk($disk)->putFileAs('logos', $this->logo, $filename);

                if ($disk === 's3') {
                    $baseUrl = rtrim(config('filesystems.disks.s3.url'), '/');
                    $config['logo_url'] = $baseUrl . '/' . $storedPath;
                } else {
                    $config['logo_url'] = Storage::disk('public')->url($storedPath);
                }

                $this->current_logo_url = $config['logo_url'];
            } catch (\Exception $e) {
                Log::error("Logo Upload Fail: " . $e->getMessage());
                session()->flash('error', 'Error al subir imagen.');
                return;
            }
        }

        // Build the new full configuration array
        $newConfig = [
            'primary' => $this->primary_color,
            'primary_color' => $this->primary_color,
            'secondary_color' => $this->secondary_color,
            'font_family' => $this->font_family,
            'theme_mode' => $this->theme_mode,
            'logo_url' => $config['logo_url'] ?? $this->current_logo_url,
        ];

        // GUARANTEED PERSISTENCE: Use direct DB update to bypass any model observer or casting issues
        $updated = DB::table('tenants')
            ->where('id', $tenant->id)
            ->update([
                'name' => $this->company_name,
                'theme_config_json' => json_encode($newConfig),
                'updated_at' => now(),
            ]);

        if ($updated) {
            // Update the model in memory to reflect changes in current session
            $tenant->refresh();
            session()->flash('message', '¡Cambios guardados y persistidos!');
        } else {
            session()->flash('error', 'No se detectaron cambios o hubo un error al guardar.');
        }

        $this->reset('logo');
    }

    public function render()
    {
        return view('livewire.builder.brand-settings')->layout('components.layouts.app');
    }
}
