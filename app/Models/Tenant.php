<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'name',
        'domain',
        'subdomain',
        'status',
        'locale',
        'plan_id',
        'settings_json',
        'features_json',
        'theme_config_json',
        'enabled_reports_json',
        'maintenance_mode_until',
        'next_billing_at',
        'payment_warning_active',
    ];

    protected $casts = [
        'settings_json' => 'array',
        'features_json' => 'array',
        'theme_config_json' => 'array',
        'enabled_reports_json' => 'array',
        'maintenance_mode_until' => 'datetime',
        'next_billing_at' => 'datetime',
        'payment_warning_active' => 'boolean',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function packages()
    {
        return $this->hasMany(Package::class);
    }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function getStripeKey()
    {
        return $this->settings_json['stripe_key'] ?? config('services.stripe.key');
    }

    public function getStripeSecret()
    {
        return $this->settings_json['stripe_secret'] ?? config('services.stripe.secret');
    }

    public function getPaypalConfig()
    {
        $settings = $this->settings_json ?? [];
        return [
            'mode'    => $settings['paypal_mode'] ?? env('PAYPAL_MODE', 'sandbox'),
            'sandbox' => [
                'client_id'         => $settings['paypal_sandbox_client_id'] ?? env('PAYPAL_SANDBOX_CLIENT_ID'),
                'client_secret'     => $settings['paypal_sandbox_client_secret'] ?? env('PAYPAL_SANDBOX_CLIENT_SECRET'),
                'app_id'            => 'APP-80W284485P519543T',
            ],
            'live' => [
                'client_id'         => $settings['paypal_live_client_id'] ?? env('PAYPAL_LIVE_CLIENT_ID'),
                'client_secret'     => $settings['paypal_live_client_secret'] ?? env('PAYPAL_LIVE_CLIENT_SECRET'),
                'app_id'            => $settings['paypal_live_app_id'] ?? env('PAYPAL_LIVE_APP_ID'),
            ],
            'payment_action' => 'Sale',
            'currency'       => $settings['paypal_currency'] ?? 'USD',
            'notify_url'     => '',
            'locale'         => 'en_US',
            'validate_ssl'   => true,
        ];
    }

    public function setMailConfig()
    {
        $settings = $this->settings_json ?? [];

        // Set dynamic app name for email templates
        config(['app.name' => $this->name]);

        if (isset($settings['mail_host']) && $settings['mail_host']) {
            config([
                'mail.mailers.smtp.host' => $settings['mail_host'],
                'mail.mailers.smtp.port' => $settings['mail_port'],
                'mail.mailers.smtp.username' => $settings['mail_username'],
                'mail.mailers.smtp.password' => $settings['mail_password'],
                'mail.mailers.smtp.encryption' => $settings['mail_encryption'],
                'mail.from.address' => $settings['mail_from_address'],
                'mail.from.name' => $settings['mail_from_name'],
            ]);

            if (app()->bound('mail.manager')) {
                app('mail.manager')->forgetMailers();
            }
        }
    }

    public function generateBoxNumber($userName, $type = 'air')
    {
        $settings = $this->settings_json ?? [];

        $templateKey = $type === 'air' ? 'box_number_template_air' : 'box_number_template_maritime';
        $prefixKey = $type === 'air' ? 'box_number_prefix_air' : 'box_number_prefix_maritime';
        $counterKey = 'box_number_counter'; // Keep a single counter for the client ID regardless of service

        $template = $settings[$templateKey] ?? ($type === 'air' ? '{PREFIX}{ID} {NAME}' : '{PREFIX}M{ID} {NAME}');
        $prefix = $settings[$prefixKey] ?? ($type === 'air' ? 'AIR' : 'SEA');
        $counter = (int) ($settings[$counterKey] ?? 1000);

        // We only increment once if generating both, but here we assume it might be called separately
        // Better logic: generate ID once, then apply to templates.
        $id = $counter + 1;

        $boxNumber = str_replace(
            ['{PREFIX}', '{ID}', '{NAME}'],
            [$prefix, $id, $userName],
            $template
        );

        return $boxNumber;
    }

    public function incrementCounter()
    {
        $settings = $this->settings_json ?? [];
        $counter = (int) ($settings['box_number_counter'] ?? 1000);
        $settings['box_number_counter'] = $counter + 1;
        $this->update(['settings_json' => $settings]);
        return $settings['box_number_counter'];
    }

    /**
     * Get the current active tenant from session or auth context.
     */
    public static function current()
    {
        $tenantId = session('tenant_id') ?? (auth()->check() ? auth()->user()->tenant_id : null);

        if ($tenantId) {
            return self::find($tenantId);
        }

        // Fallback for development or missing session
        return self::first();
    }

    /**
     * URL de acceso (login) para este tenant.
     */
    public function getLoginUrl(): string
    {
        $slug = $this->login_url_slug ?? ($this->subdomain ?? $this->uuid);
        return route('tenant.access', $slug);
    }

    /**
     * URL de registro para este tenant.
     */
    public function getRegisterUrl(): string
    {
        $slug = $this->login_url_slug ?? ($this->subdomain ?? $this->uuid);
        return route('tenant.join', $slug);
    }

    /**
     * Genera la URL del logo de forma dinámica.
     */
    public function getLogoUrl(): ?string
    {
        $subdomain = strtolower($this->subdomain);
        // Intentar extensiones comunes en minúsculas y mayúsculas para Linux
        $extensions = ['png', 'jpg', 'jpeg', 'svg', 'webp', 'PNG', 'JPG', 'JPEG'];

        // 1. Prioridad: Archivo manual en public/logos/
        foreach ($extensions as $ext) {
            $filename = "{$subdomain}.{$ext}";
            if (file_exists(public_path("logos/{$filename}"))) {
                return asset("logos/{$filename}");
            }
        }

        // 2. Fallback: Archivo específico guardado en theme_config_json
        $logo = $this->theme_config_json['logo_url'] ?? null;
        if ($logo) {
            // Si el archivo físico existe en public/logos/, usarlo prioritariamente (evita problemas de symlinks/storage en producción)
            $filename = basename($logo);
            if (file_exists(public_path('logos/' . $filename))) {
                return asset('logos/' . $filename);
            }

            if (filter_var($logo, FILTER_VALIDATE_URL)) {
                return $logo;
            }
            // Asegurar que siempre se busque dentro de 'logos/'
            $cleanPath = ltrim(str_replace('logos/', '', $logo), '/');
            if (file_exists(public_path('logos/' . $cleanPath))) {
                return asset('logos/' . $cleanPath);
            }
        }

        // 3. Fallback: Cloudflare R2 o S3 si está configurado
        if (env('AWS_URL') && $logo) {
            return rtrim(env('AWS_URL'), '/') . '/' . ltrim($logo, '/');
        }

        return null;
    }

    /**
     * Get the status of a main module/section ('active', 'hidden', 'disabled').
     */
    public function getFeatureStatus(string $module): string
    {
        $features = $this->features_json ?? [];
        return $features['modules'][$module] ?? 'active';
    }

    /**
     * Check if a granular sub-feature is allowed.
     */
    public function hasSubFeature(string $subFeature): bool
    {
        $features = $this->features_json ?? [];
        return (bool) ($features['sub_features'][$subFeature] ?? true);
    }

    /**
     * Determine if Net Profit should subtract provider costs.
     */
    public function shouldSubtractProviderCosts(): bool
    {
        $settings = $this->settings_json ?? [];
        return (bool) ($settings['subtract_provider_costs'] ?? true);
    }
}
