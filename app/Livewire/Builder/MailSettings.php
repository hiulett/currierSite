<?php

namespace App\Livewire\Builder;

use Livewire\Component;
use App\Models\Tenant;

class MailSettings extends Component
{
    public $mail_host;
    public $mail_port;
    public $mail_username;
    public $mail_password;
    public $mail_encryption = 'tls';
    public $mail_from_address;
    public $mail_from_name;
    public $mail_driver = 'smtp'; // smtp or sendgrid_api
    public $test_email_address;
    
    public $invoice_email_template;
    public $quotation_email_template;

    public function mount()
    {
        $tenant = Tenant::current();
        if (!$tenant) return;

        $settings = $tenant->settings_json ?? [];

        $this->mail_host = $settings['mail_host'] ?? '';
        $this->mail_port = $settings['mail_port'] ?? '587';
        $this->mail_username = $settings['mail_username'] ?? '';
        $this->mail_password = $settings['mail_password'] ?? '';
        $this->mail_encryption = $settings['mail_encryption'] ?? 'tls';
        $this->mail_from_address = $settings['mail_from_address'] ?? '';
        $this->mail_from_name = $settings['mail_from_name'] ?? $tenant->name;
        $this->mail_driver = $settings['mail_driver'] ?? 'smtp';
        
        $this->invoice_email_template = $settings['invoice_email_template'] ?? "Hola {nombre_cliente},\n\nSe ha generado una nueva factura por tus servicios de logística.\n\nNúmero de Factura: #{numero_documento}\nMonto Total: {monto_total}\nFecha de Vencimiento: {fecha_vencimiento}\n\nGracias por confiar en nosotros.\n\nSaludos,\n{nombre_empresa}";
        $this->quotation_email_template = $settings['quotation_email_template'] ?? "Hola {nombre_cliente},\n\nLe hemos generado la cotización #{numero_documento}.\n\nAdjunto a este correo encontrará el documento en formato PDF con todos los detalles y condiciones de los servicios cotizados.\n\nMonto Total: {monto_total}\n\nSi tiene alguna duda o requiere asistencia adicional, no dude en contactarnos.\n\nGracias por su preferencia,\n{nombre_empresa}";

        if (empty($this->test_email_address)) {
            $this->test_email_address = auth()->user()->email ?? '';
        }
    }

    public function save()
    {
        $tenant = Tenant::current();
        $settings = $tenant->settings_json ?? [];

        $settings['mail_host'] = trim($this->mail_host);
        $settings['mail_port'] = trim($this->mail_port);
        $settings['mail_username'] = trim($this->mail_username);
        $settings['mail_password'] = trim($this->mail_password);
        $settings['mail_encryption'] = trim($this->mail_encryption);
        $settings['mail_from_address'] = trim($this->mail_from_address);
        $settings['mail_from_name'] = trim($this->mail_from_name);
        $settings['mail_driver'] = $this->mail_driver;
        
        $canCustomizeTemplates = $tenant ? $tenant->hasSubFeature('customize_mail_templates') : true;
        if ($canCustomizeTemplates) {
            $settings['invoice_email_template'] = trim($this->invoice_email_template);
            $settings['quotation_email_template'] = trim($this->quotation_email_template);
        }

        $tenant->update(['settings_json' => $settings]);
        
        // Reload from database to guarantee it was saved
        $tenant->refresh();

        session()->flash('message', 'Configuración de correo verificada y guardada correctamente.');
    }

    public function sendTestMail()
    {
        if ($this->mail_driver === 'smtp') {
            $this->validate([
                'mail_host' => 'required',
                'mail_port' => 'required',
                'mail_username' => 'required',
                'mail_password' => 'required',
                'mail_from_address' => 'required|email',
            ]);
        } else {
            $this->validate([
                'mail_password' => 'required', // Here it's the API Key
                'mail_from_address' => 'required|email',
            ]);
        }

        $this->save();

        try {
            if ($this->mail_driver === 'sendgrid_api') {
                return $this->sendViaSendGridApi();
            }

            // SMTP Logic...
            config([
                'mail.default' => 'smtp',
                'mail.mailers.smtp.host' => $this->mail_host,
                'mail.mailers.smtp.port' => $this->mail_port,
                'mail.mailers.smtp.username' => $this->mail_username,
                'mail.mailers.smtp.password' => $this->mail_password,
                'mail.mailers.smtp.encryption' => $this->mail_encryption,
                'mail.mailers.smtp.timeout' => 30,
                'mail.from.address' => $this->mail_from_address,
                'mail.from.name' => $this->mail_from_name,
            ]);

            // Force Laravel to drop cached instances and use new config
            \Illuminate\Support\Facades\Mail::purge('smtp');
            \Illuminate\Support\Facades\Mail::purge($this->mail_driver);

            $tenantName = \App\Models\Tenant::current()?->name ?? config('app.name');
            \Illuminate\Support\Facades\Mail::raw("Este es un correo de prueba de {$tenantName} para validar tu configuración SMTP. Si recibiste esto, ¡todo está funcionando correctamente!", function ($message) use ($tenantName) {
                $message->to($this->test_email_address)
                        ->subject("Prueba de Configuración de Correo - {$tenantName}");
            });

            session()->flash('message', '¡Éxito! Correo de prueba enviado (vía SMTP) a: ' . $this->test_email_address);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Test Mail Error: ' . $e->getMessage());
            session()->flash('error', 'Error al enviar el correo: ' . $e->getMessage());
        }
    }

    protected function sendViaSendGridApi()
    {
        $response = \Illuminate\Support\Facades\Http::withToken($this->mail_password)
            ->post('https://api.sendgrid.com/v3/mail/send', [
                'personalizations' => [
                    [
                        'to' => [['email' => $this->test_email_address]],
                    ]
                ],
                'from' => [
                    'email' => $this->mail_from_address,
                    'name' => $this->mail_from_name
                ],
                'subject' => 'Prueba de Configuración - SendGrid API',
                'content' => [
                    [
                        'type' => 'text/plain',
                        'value' => 'Este es un correo de prueba enviado a través de la API oficial de SendGrid desde ' . (\App\Models\Tenant::current()?->name ?? config('app.name')) . '.'
                    ]
                ]
            ]);

        if ($response->successful()) {
            session()->flash('message', '¡Éxito! Correo de prueba enviado vía API de SendGrid a ' . $this->test_email_address);
        } else {
            $error = $response->json();
            $errorMessage = $error['errors'][0]['message'] ?? 'Error desconocido en la API';
            session()->flash('error', 'Error SendGrid API: ' . $errorMessage);
        }
    }

    public function render()
    {
        return view('livewire.builder.mail-settings')->layout('components.layouts.app');
    }
}
