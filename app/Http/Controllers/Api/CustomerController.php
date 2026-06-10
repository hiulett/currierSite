<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Package;
use App\Models\Invoice;

use App\Models\AssistedPurchase;
use Illuminate\Support\Str;

class CustomerController extends Controller
{
    public function profile(Request $request)
    {
        $user = $request->user();
        $customer = $user->customer;

        if (!$customer) {
            return response()->json(['message' => 'Customer profile not found'], 404);
        }

        // Generate referral code if missing
        if (!$customer->referral_code) {
            $customer->update(['referral_code' => strtoupper(Str::random(8))]);
        }

        return response()->json([
            'user' => $user,
            'customer' => $customer,
            'locker' => $customer->locker,
            'tenant' => $user->tenant,
            'referral_link' => config('app.url') . "/register?ref=" . $customer->referral_code,
        ]);
    }

    public function assistedPurchases(Request $request)
    {
        $customer = $request->user()->customer;
        if (!$customer) return response()->json([]);

        $purchases = AssistedPurchase::where('customer_id', $customer->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($purchases);
    }

    public function storeAssistedPurchase(Request $request)
    {
        $request->validate([
            'url' => 'required|url',
            'description' => 'nullable|string',
            'estimated_price' => 'nullable|numeric',
        ]);

        $customer = $request->user()->customer;

        $purchase = AssistedPurchase::create([
            'tenant_id' => $customer->tenant_id,
            'customer_id' => $customer->id,
            'url' => $request->url,
            'description' => $request->description,
            'estimated_price' => $request->estimated_price,
            'status' => 'pending',
        ]);

        return response()->json($purchase, 201);
    }

    public function packages(Request $request)
    {
        $customer = $request->user()->customer;

        if (!$customer) return response()->json([]);

        $packages = Package::where('customer_id', $customer->id)
            ->with(['warehouse', 'shipment'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($packages);
    }

    public function packageDetail(Request $request, $id)
    {
        $customer = $request->user()->customer;
        $package = Package::where('customer_id', $customer->id)
            ->where('id', $id)
            ->with(['trackingEvents' => function($q) {
                $q->orderBy('created_at', 'desc');
            }, 'warehouse', 'shipment'])
            ->firstOrFail();

        return response()->json($package);
    }

    public function invoices(Request $request)
    {
        $customer = $request->user()->customer;
        if (!$customer) return response()->json([]);

        $invoices = Invoice::where('customer_id', $customer->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($invoices);
    }

    public function balance(Request $request)
    {
        $customer = $request->user()->customer;
        if (!$customer) return response()->json(['balance' => 0]);

        return response()->json([
            'balance' => $customer->balance ?? 0,
            'points' => $customer->points ?? 0,
            'currency' => 'USD',
        ]);
    }
}
