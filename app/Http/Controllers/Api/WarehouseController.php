<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\PreAlert;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function scan(Request $request)
    {
        $request->validate([
            'tracking' => 'required|string',
        ]);

        $tracking = $request->tracking;

        // 1. Search for existing package
        $package = Package::where('tracking_number', $tracking)->first();
        if ($package) {
            return response()->json([
                'found' => true,
                'type' => 'package',
                'data' => $package,
                'message' => 'Package already exists in system'
            ]);
        }

        // 2. Search for pre-alert
        $preAlert = PreAlert::where('tracking_number', $tracking)->first();
        if ($preAlert) {
            return response()->json([
                'found' => true,
                'type' => 'pre_alert',
                'data' => $preAlert,
                'message' => 'Pre-alert found for this tracking'
            ]);
        }

        return response()->json([
            'found' => false,
            'message' => 'No record found. Ready for new reception.'
        ]);
    }

    public function warehouses()
    {
        return response()->json(\App\Models\Warehouse::all(['id', 'name', 'code']));
    }

    public function bulkReceive(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.tracking_number' => 'required|string',
            'items.*.weight' => 'required|numeric',
            'warehouse_id' => 'required|exists:warehouses,id',
        ]);

        $results = [];
        $warehouseId = $request->warehouse_id;
        $tenantId = auth()->user()->tenant_id;

        foreach ($request->items as $item) {
            // Check if already exists to avoid duplicates in this batch
            $package = Package::updateOrCreate(
                ['tracking_number' => $item['tracking_number'], 'tenant_id' => $tenantId],
                [
                    'weight' => $item['weight'],
                    'warehouse_id' => $warehouseId,
                    'status' => 'received_miami', // Logic could be more dynamic
                ]
            );

            $results[] = $package;
        }

        return response()->json([
            'success' => true,
            'processed_count' => count($results),
            'items' => $results
        ]);
    }
}
