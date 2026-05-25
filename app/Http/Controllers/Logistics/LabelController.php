<?php

namespace App\Http\Controllers\Logistics;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Barryvdh\DomPDF\Facade\Pdf;

class LabelController extends Controller
{
    public function print(Package $package)
    {
        $package->load(['customer.user', 'warehouse']);

        // Custom size for thermal printer (4x6 inches is approx 288x432 pts)
        $pdf = Pdf::loadView('logistics.label-pdf', compact('package'))
            ->setPaper([0, 0, 288, 432], 'portrait');

        return $pdf->stream('Label_' . $package->tracking_number . '.pdf');
    }
}
