<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use App\Notifications\PaymentReminder;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendPaymentReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-payment-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envía recordatorios de pago automáticos para facturas pendientes y vencidas';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();

        // 1. Recordatorio 7 días antes
        $before7 = $today->copy()->addDays(7);
        $this->sendReminders($before7, 'before');

        // 2. Recordatorio al vencimiento
        $this->sendReminders($today, 'on_due');

        // 3. Recordatorio 7 días después
        $after7 = $today->copy()->subDays(7);
        $this->sendReminders($after7, 'after_7');

        // 4. Recordatorio 30 días después
        $after30 = $today->copy()->subDays(30);
        $this->sendReminders($after30, 'after_30');

        $this->info('Recordatorios procesados correctamente.');
    }

    protected function sendReminders(Carbon $date, string $type)
    {
        $invoices = Invoice::whereDate('due_date', $date)
            ->whereIn('status', ['pending', 'partial'])
            ->with('customer.user')
            ->get();

        foreach ($invoices as $invoice) {
            if ($invoice->customer && $invoice->customer->user) {
                $invoice->customer->user->notify(new PaymentReminder($invoice, $type));
                $this->line("Notificación {$type} enviada a: {$invoice->customer->user->email} para factura #{$invoice->number}");
            }
        }
    }
}
