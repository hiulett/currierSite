<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Estado del envío del correo de la factura:
     * - email_queued_at: cuándo se puso en cola (permite ver si el worker no consume).
     * - email_error: motivo del último fallo de envío.
     */
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->timestamp('email_queued_at')->nullable()->after('email_sent_at');
            $table->text('email_error')->nullable()->after('email_queued_at');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['email_queued_at', 'email_error']);
        });
    }
};
