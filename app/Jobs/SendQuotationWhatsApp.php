<?php

namespace App\Jobs;

use App\Models\Quotation;
use App\Services\WhatsAppService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendQuotationWhatsApp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Quotation $quotation;

    public int $tries = 3;

    public function __construct(Quotation $quotation)
    {
        $this->quotation = $quotation;
    }

    public function handle(WhatsAppService $service): void
    {
        try {
            $result = $service->sendQuotation($this->quotation);
            Log::info('WhatsApp quotation result', [
                'quotation' => $this->quotation->number,
                'ok' => $result['ok'] ?? false,
                'error' => $result['error'] ?? null,
            ]);
        } catch (\Throwable $e) {
            Log::error('WhatsApp quotation job failed: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);
        }
    }
}
