<?php

namespace App\Livewire\Concerns;

use App\Models\Tenant;
use App\Services\WhatsAppService;

/**
 * Expone a las vistas si el tenant actual usa WhatsApp Web, para renderizar
 * el enlace a pestaña nueva en lugar del botón que encola por la API.
 */
trait ResolvesWhatsAppChannel
{
    protected ?bool $resolvedWhatsAppWeb = null;

    public function whatsappChannelIsWeb(): bool
    {
        if ($this->resolvedWhatsAppWeb !== null) {
            return $this->resolvedWhatsAppWeb;
        }

        $tenant = Tenant::find(session('tenant_id'));

        return $this->resolvedWhatsAppWeb = $tenant !== null
            && app(WhatsAppService::class)->channelName($tenant) === 'web';
    }
}
