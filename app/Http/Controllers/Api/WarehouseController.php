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

    public function receive(Request $request)
    {
        $request->validate([
            'tracking_number' => 'required|string',
            'weight' => 'required|numeric',
            'description' => 'nullable|string',
            'customer_id' => 'nullable|exists:customers,id',
            'warehouse_id' => 'required|exists:warehouses,id',
        ]);

        // Logic to create or update package
        // This should probably reuse logic from SmartReception service if available

        $package = Package::create([
            'tenant_id' => auth()->user()->tenant_id,
            'tracking_number' => $request->tracking_number,
            'weight' => $request->weight,
            'description' => $request->description,
            'customer_id' => $request->customer_id,
            'warehouse_id' => $request->warehouse_id,
            'status' => 'received_miami', // Or based on warehouse location
        ]);

        return response()->json([
            'success' => true,
            'package' => $package,
            'message' => 'Package received successfully'
        ]);
    }
}
