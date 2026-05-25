<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaymentController extends Controller
{
    public function checkout(Invoice $invoice)
    {
        // Security check
        if (!$invoice->customer || $invoice->customer->user_id !== auth()->id()) {
            abort(403, 'No tienes permiso para pagar esta factura.');
        }

        if ($invoice->status === 'paid') {
            return redirect()->back()->with('error', 'Esta factura ya está pagada.');
        }

        $tenant = $invoice->tenant;
        $stripeSecret = $tenant->getStripeSecret();

        if (!$stripeSecret) {
            return redirect()->back()->with('error', 'El sistema de pagos con tarjeta no está configurado para este courier.');
        }

        Stripe::setApiKey($stripeSecret);

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => strtolower($invoice->currency ?? 'usd'),
                    'product_data' => [
                        'name' => 'Factura #' . $invoice->number,
                    ],
                    'unit_amount' => (int) ($invoice->total * 100),
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('payment.success', ['invoice' => $invoice->id]),
            'cancel_url' => route('payment.cancel', ['invoice' => $invoice->id]),
            'metadata' => [
                'invoice_id' => $invoice->id,
                'tenant_id' => $tenant->id,
            ],
        ]);

        return redirect($session->url);
    }

    public function paypalCheckout(Invoice $invoice)
    {
        // Security check
        if (!$invoice->customer || $invoice->customer->user_id !== auth()->id()) {
            abort(403, 'No tienes permiso para pagar esta factura.');
        }

        if ($invoice->status === 'paid') {
            return redirect()->back()->with('error', 'Esta factura ya está pagada.');
        }

        $tenant = $invoice->tenant;
        $paypalConfig = $tenant->getPaypalConfig();

        if (!$paypalConfig['sandbox']['client_id'] && !$paypalConfig['live']['client_id']) {
            return redirect()->back()->with('error', 'El sistema de pagos con PayPal no está configurado para este courier.');
        }

        $provider = new PayPalClient;
        $provider->setApiCredentials($paypalConfig);
        $provider->getAccessToken();

        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('payment.paypal.success', ['invoice' => $invoice->id]),
                "cancel_url" => route('payment.cancel', ['invoice' => $invoice->id]),
            ],
            "purchase_units" => [
                0 => [
                    "amount" => [
                        "currency_code" => strtoupper($invoice->currency ?? 'USD'),
                        "value" => number_format($invoice->total, 2, '.', '')
                    ],
                    "description" => 'Factura #' . $invoice->number
                ]
            ]
        ]);

        if (isset($response['id']) && $response['id'] != null) {
            foreach ($response['links'] as $links) {
                if ($links['rel'] == 'approve') {
                    return redirect()->away($links['href']);
                }
            }
            return redirect()->back()->with('error', 'Algo salió mal con PayPal.');
        } else {
            return redirect()->back()->with('error', $response['message'] ?? 'Error al conectar con PayPal.');
        }
    }

    public function paypalSuccess(Request $request, Invoice $invoice)
    {
        $tenant = $invoice->tenant;
        $paypalConfig = $tenant->getPaypalConfig();

        if (!$paypalConfig['sandbox']['client_id'] && !$paypalConfig['live']['client_id']) {
            return redirect()->back()->with('error', 'El sistema de pagos con PayPal no está configurado para este courier.');
        }

        $provider = new PayPalClient;
        $provider->setApiCredentials($paypalConfig);
        $provider->getAccessToken();

        $response = $provider->capturePaymentOrder($request['token']);

        if (isset($response['status']) && $response['status'] == 'COMPLETED') {
            $invoice->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);

            return redirect()->route('customer.invoices')->with('message', '¡Pago con PayPal procesado exitosamente!');
        } else {
            return redirect()->route('customer.invoices')->with('error', $response['message'] ?? 'El pago no pudo ser completado.');
        }
    }

    public function success(Invoice $invoice)
    {
        $invoice->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        return redirect()->route('customer.invoices')->with('message', '¡Pago con Tarjeta procesado exitosamente!');
    }

    public function cancel(Invoice $invoice)
    {
        return redirect()->route('customer.invoices')->with('error', 'El pago fue cancelado.');
    }
}
