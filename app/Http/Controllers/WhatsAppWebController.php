<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Quotation;
use App\Models\Tenant;
use App\Models\User;
use App\Services\WhatsApp\PasswordResetLink;
use App\Services\WhatsAppService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Puntos de entrada para el canal WhatsApp Web.
 *
 * Cada ruta genera el mensaje y devuelve un 302 a web.whatsapp.com. Se abren
 * con target="_blank", así el usuario conserva la pantalla actual y el
 * navegador reutiliza su sesión de WhatsApp Web.
 */
class WhatsAppWebController extends Controller
{
    public function __construct(protected WhatsAppService $whatsapp) {}

    public function invoice(Invoice $invoice): RedirectResponse
    {
        if ($blocked = $this->guard($invoice->tenant_id)) {
            return $blocked;
        }

        return $this->deliver($this->whatsapp->sendInvoice($invoice));
    }

    public function quotation(Quotation $quotation): RedirectResponse
    {
        if ($blocked = $this->guard($quotation->tenant_id)) {
            return $blocked;
        }

        return $this->deliver($this->whatsapp->sendQuotation($quotation));
    }

    public function passwordReset(Customer $customer): RedirectResponse
    {
        if ($blocked = $this->guard($customer->tenant_id)) {
            return $blocked;
        }

        $user = $customer->user_id ? User::find($customer->user_id) : null;

        if (! $user) {
            return back()->with('error', 'El cliente no tiene un usuario asociado.');
        }

        if (! $customer->phone) {
            return back()->with('error', 'El cliente no tiene un teléfono registrado.');
        }

        $link = app(PasswordResetLink::class)->build($user, $customer->phone);

        if (! $link['whatsapp']) {
            return back()->with('error', 'No se pudo construir el enlace de WhatsApp.');
        }

        return redirect()->away($link['whatsapp']);
    }

    public function test(Request $request): RedirectResponse
    {
        $tenant = Tenant::find(session('tenant_id'));

        if (! $tenant) {
            return back()->with('error', 'Tenant no encontrado.');
        }

        if ($blocked = $this->guard($tenant->id)) {
            return $blocked;
        }

        $number = $request->query('numero') ?: $request->query('negocio');

        if (! $number) {
            return back()->with('error', 'Indica un número de prueba o el número de negocio para probar la conexión.');
        }

        return $this->deliver($this->whatsapp->sendTestMessage($tenant, $number));
    }

    /**
     * Solo se permite generar enlaces cuando el tenant usa el canal web.
     */
    protected function guard(?int $tenantId): ?RedirectResponse
    {
        $tenant = $tenantId ? Tenant::find($tenantId) : null;

        if (! $tenant) {
            return back()->with('error', 'Tenant no encontrado.');
        }

        if ($this->whatsapp->channelName($tenant) !== 'web') {
            return back()->with('error', 'El canal WhatsApp Web no está activo. Actívalo en Configuración → Pagos e Integraciones.');
        }

        return null;
    }

    /**
     * @param  array{ok?: bool, url?: ?string, error?: ?string}  $result
     */
    protected function deliver(array $result): RedirectResponse
    {
        if (! ($result['ok'] ?? false) || empty($result['url'])) {
            return back()->with('error', $result['error'] ?? 'No se pudo preparar el envío por WhatsApp Web.');
        }

        return redirect()->away($result['url']);
    }
}
