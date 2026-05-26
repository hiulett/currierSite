<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ExternalTrackingService
{
    /**
     * Track a package using InstantParcels API (USA/International leg)
     */
    public function trackInstantParcels($trackingNumber)
    {
        $url = "https://api.instantparcels.com/v1/tracking/" . trim($trackingNumber);

        try {
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Origin' => 'https://instantparcels.com',
                'Referer' => 'https://instantparcels.com/',
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            ])->get($url);

            if ($response->successful()) {
                $data = $response->json();

                $history = [];
                if (isset($data['events'])) {
                    foreach ($data['events'] as $event) {
                        $location = isset($event['location_obj']['city']) ? $event['location_obj']['city'] . ', ' . ($event['location_obj']['countryISO'] ?? '') : ($event['location_obj']['countryISO'] ?? 'Unknown');

                        $history[] = [
                            'status' => strtoupper($event['status'] ?? $event['name']),
                            'date' => Carbon::parse($event['date'])->format('d M, Y H:i'),
                            'location' => $location,
                            'notes' => $event['name'] ?? '',
                            'source' => 'International'
                        ];
                    }
                }

                return [
                    'tracking' => $data['code'] ?? $trackingNumber,
                    'status' => $data['status'] ?? 'IN TRANSIT',
                    'carrier' => $data['shipmentInfo']['carrier']['name'] ?? 'Detected Carrier',
                    'origin' => ($data['origin']['city'] ?? '') . ' ' . ($data['origin']['countryISO'] ?? ''),
                    'destination' => ($data['destination']['city'] ?? '') . ' ' . ($data['destination']['countryISO'] ?? ''),
                    'history' => $history
                ];
            }
            return null;
        } catch (\Exception $e) {
            Log::error("InstantParcels API Error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Track a package using FuzionCargo's external API (Panama local leg).
     */
    public function trackFuzionCargo($trackingNumber)
    {
        $url = "https://app.fuzioncargo.com/index.php/v3/package/" . trim($trackingNumber);

        try {
            $response = Http::withHeaders([
                'Accept' => '*/*',
                'Origin' => 'https://fuzioncargo.com',
                'Referer' => 'https://fuzioncargo.com/',
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            ])->get($url);

            if ($response->successful()) {
                $data = $response->json();
                $history = [];

                if (isset($data['history'])) {
                    foreach ($data['history'] as $statusKey => $details) {
                        if ($details && isset($details['date'])) {
                            $history[] = [
                                'status' => strtoupper($statusKey),
                                'date' => Carbon::parse($details['date'])->format('d M, Y H:i'),
                                'location' => 'Panama Delivery Center',
                                'notes' => "Procesado en fase local: " . $statusKey,
                                'source' => 'Local Panama'
                            ];
                        }
                    }
                }

                return [
                    'tracking' => $data['tracking'] ?? $trackingNumber,
                    'status' => count($history) > 0 ? $history[0]['status'] : 'LOCAL PROCESSING',
                    'weight' => $data['weight'] ?? '0.00',
                    'history' => $history
                ];
            }
            return null;
        } catch (\Exception $e) {
            Log::error("FuzionCargo API Error: " . $e->getMessage());
            return null;
        }
    }
}
