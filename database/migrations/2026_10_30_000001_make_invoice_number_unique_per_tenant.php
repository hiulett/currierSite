<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * El número de factura era único a nivel global, lo que provocaba
     * colisiones entre tenants y dentro del mismo tenant (rand(100, 999)).
     * Ahora es único por tenant.
     */
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropUnique(['number']);
            $table->unique(['tenant_id', 'number'], 'invoices_tenant_number_unique');
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropUnique('invoices_tenant_number_unique');
            $table->unique(['number']);
        });
    }
};
