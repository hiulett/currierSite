<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExpenseAttachmentController extends Controller
{
    public function __invoke(Expense $expense): StreamedResponse
    {
        $path = $expense->attachmentRelativePath();

        abort_if(! $path, 404);

        foreach (Expense::attachmentDisks() as $disk) {
            try {
                $storage = Storage::disk($disk);

                if ($storage->exists($path)) {
                    return $storage->response($path, $expense->attachmentFilename());
                }
            } catch (\Throwable $e) {
                Log::warning("No se pudo leer el adjunto del egreso {$expense->id} en el disco {$disk}: ".$e->getMessage());
            }
        }

        abort(404);
    }
}
