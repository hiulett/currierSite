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

    // Campos de personalización de pantalla Login/Registro
    public $login_url_slug;
    public $login_bg_color;
    public $login_bg_image_url;
    public $login_welcome_title;
    public $login_welcome_subtitle;
    public $show_register_link = true;
    public $custom_css;

    public function mount()
    {
        $tenant = $this->getTenant();
        $this->loadData($tenant);
    }

    protected function getTenant()
    {
        // Usar el usuario autenticado como fuente prioritaria (más seguro que sesión)
        if (auth()->check() && auth()->user()->tenant_id) {
            return Tenant::findOrFail(auth()->user()->tenant_id);
        }
        $id = session('tenant_id');
        if ($id) {
            return Tenant::findOrFail($id);
        }
        abort(403, 'No hay contexto de tenant.');
    }

    protected function loadData($tenant)
    {
        $this->company_name = $tenant->name;
        $config = $tenant->theme_config_json ?? [];

        $this->primary_color     = $config['primary_color'] ?? ($config['primary'] ?? '#2563eb');
        $this->secondary_color   = $config['secondary_color'] ?? '#0b5ed7';
        $this->font_family       = $config['font_family'] ?? 'figtree';
        $this->theme_mode        = $config['theme_mode'] ?? 'light';
        $this->current_logo_url  = $tenant->getLogoUrl(); // Usar el método dinámico aquí

        // Campos de la pantalla Login/Registro
        $this->login_url_slug        = $tenant->login_url_slug ?? ($tenant->subdomain ?? $tenant->uuid);
        $this->login_bg_color        = $config['login_bg_color'] ?? '#F8FAFC';
        $this->login_bg_image_url    = $config['login_bg_image_url'] ?? null;
        $this->login_welcome_title   = $config['login_welcome_title'] ?? $tenant->name;
        $this->login_welcome_subtitle= $config['login_welcome_subtitle'] ?? 'Acceso al Portal';
        $this->show_register_link    = $config['show_register_link'] ?? true;
        $this->custom_css            = $config['custom_css'] ?? '';
    }

    public function save()
    {
        $tenant = $this->getTenant();
        $canChangeName = $tenant->hasSubFeature('change_company_name');
        $canCustomizeBrand = $tenant->hasSubFeature('customize_visual_brand');

        $rules = [];
        if ($canChangeName) {
            $rules['company_name'] = 'required|min:3';
        }
        if ($canCustomizeBrand) {
            $rules['logo']               = 'nullable|image|max:2048';
            $rules['login_url_slug']     = 'nullable|alpha_dash|max:80|unique:tenants,login_url_slug,' . $tenant->id;
            $rules['login_bg_color']     = 'nullable|max:20';
            $rules['login_bg_image_url'] = 'nullable|url|max:500';
            $rules['login_welcome_title']= 'nullable|max:100';
            $rules['custom_css']         = 'nullable|max:5000';
        }

        if (empty($rules)) {
            session()->flash('error', 'No tiene permisos para modificar la configuración de marca.');
            return;
        }

        $this->validate($rules);

        // Cargamos la configuración actual para no perder otros datos
        $config = $tenant->theme_config_json ?? [];

        // 1. Manejo del Logo
        if ($canCustomizeBrand && $this->logo) {
            try {
                $filename = 'logo_' . $tenant->id . '_' . time() . '.' . $this->logo->getClientOriginalExtension();
                $disk = !empty(config('filesystems.disks.s3.key')) ? 's3' : 'public';

                // Guardamos el archivo con visibilidad pública
                $path = $this->logo->storeAs('logos', $filename, [
                    'disk' => $disk,
                    'visibility' => 'public'
                ]);

                // Generamos la URL
                $url = Storage::disk($disk)->url($path);

                // Corrección específica para tu Cloudflare R2
                if ($disk === 's3') {
                    $r2PublicUrl = 'https://pub-4bb2c00e758b4dbaa870bf03ba604b56.r2.dev';

                    // Si el sistema no devuelve una URL absoluta o devuelve el endpoint de la API
                    if (!str_starts_with($url, 'http') || str_contains($url, 'cloudflarestorage.com')) {
                        $url = rtrim($r2PublicUrl, '/') . '/' . ltrim($path, '/');
                    }
                }

                $config['logo_url'] = $url;
                $this->current_logo_url = $url;

                Log::info("Logo guardado y accesible en: " . $url);
            } catch (\Exception $e) {
                Log::error("Error subiendo logo: " . $e->getMessage());
                session()->flash('error', 'Error al subir el logo: ' . $e->getMessage());
                return;
            }
        } elseif (!$this->logo) {
            $config['logo_url'] = $this->current_logo_url;
        }

        // 2. Actualizar el resto de la configuración
        if ($canCustomizeBrand) {
            $config['primary']            = $this->primary_color;
            $config['primary_color']      = $this->primary_color;
            $config['secondary_color']    = $this->secondary_color;
            $config['font_family']        = $this->font_family;
            $config['theme_mode']         = $this->theme_mode;

            // Campos de personalización de la pantalla Login/Registro
            $config['login_bg_color']         = $this->login_bg_color;
            $config['login_bg_image_url']     = $this->login_bg_image_url;
            $config['login_welcome_title']    = $this->login_welcome_title;
            $config['login_welcome_subtitle'] = $this->login_welcome_subtitle;
            $config['show_register_link']     = (bool) $this->show_register_link;
            $config['custom_css']             = $this->custom_css;
        }

        // 3. Persistencia en Base de Datos
        if ($canChangeName) {
            $tenant->name = $this->company_name;
        }
        $tenant->theme_config_json = $config;

        // Guardar login_url_slug si se proporcionó
        if ($canCustomizeBrand && !empty($this->login_url_slug)) {
            $tenant->login_url_slug = strtolower(trim($this->login_url_slug));
        }

        if ($tenant->save()) {
            session()->flash('message', 'Configuración guardada exitosamente.');
            $this->reset('logo');
            $this->loadData($tenant); // Recargar datos para confirmar sincronización
        } else {
            session()->flash('error', 'No se pudo guardar la configuración en la base de datos.');
        }
    }

    public function render()
    {
        return view('livewire.builder.brand-settings')->layout('components.layouts.app');
    }
}
