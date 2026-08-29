<?php

namespace App\Http\Controllers\Logistics;

use App\Exports\InventoryExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function exportInventory()
    {
        return Excel::download(new InventoryExport, 'Inventario_'.date('Y-m-d').'.xlsx');
    }
}
