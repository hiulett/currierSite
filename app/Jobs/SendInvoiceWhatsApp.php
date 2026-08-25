<?php

namespace App\Jobs;

use App\Models\Invoice;
use App\Services\WhatsAppService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendInvoiceWhatsApp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Invoice $invoice;

    public int $tries = 3;

    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    public function handle(WhatsAppService $service): void
    {
        try {
            $result = $service->sendInvoice($this->invoice);
            Log::info('WhatsApp invoice result', [
                'invoice' => $this->invoice->number,
                'ok' => $result['ok'] ?? false,
                'error' => $result['error'] ?? null,
            ]);
        } catch (\Throwable $e) {
            Log::error('WhatsApp invoice job failed: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);
        }
    }
}
