<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ExternalTrackingService
{
    /**
     * Track a package using FuzionCargo's external API.
     *
     * @param string $trackingNumber
     * @return array|null
     */
    public function trackFuzionCargo($trackingNumber)
    {
        $url = "https://app.fuzioncargo.com/index.php/v3/package/" . trim($trackingNumber);

        try {
            $response = Http::withHeaders([
                'Accept' => '*/*',
                'Accept-Language' => 'es-ES,es;q=0.9,en;q=0.8',
                'Cache-Control' => 'no-cache',
                'Origin' => 'https://fuzioncargo.com',
                'Pragma' => 'no-cache',
                'Referer' => 'https://fuzioncargo.com/',
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'sec-ch-ua' => '"Not_A Brand";v="8", "Chromium";v="120", "Google Chrome";v="120"',
                'sec-ch-ua-mobile' => '?0',
                'sec-ch-ua-platform' => '"Windows"',
            ])->get($url);

            if ($response->successful()) {
                return $response->json();
            }

            Log::warning("FuzionCargo API search failed for tracking: $trackingNumber. Status: " . $response->status());
            return null;

        } catch (\Exception $e) {
            Log::error("Error connecting to FuzionCargo API: " . $e->getMessage());
            return null;
        }
    }
}
