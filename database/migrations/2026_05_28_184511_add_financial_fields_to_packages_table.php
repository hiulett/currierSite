<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->decimal('provider_cost', 10, 2)->nullable()->after('invoice_url')->comment('Costo cobrado por el proveedor logístico');
            $table->decimal('provider_weight', 10, 2)->nullable()->after('provider_cost')->comment('Peso registrado por el proveedor');
            $table->decimal('provider_length', 10, 2)->nullable()->after('provider_weight');
            $table->decimal('provider_width', 10, 2)->nullable()->after('provider_length');
            $table->decimal('provider_height', 10, 2)->nullable()->after('provider_width');
            $table->string('provider_tracking', 100)->nullable()->after('provider_height');
            $table->decimal('client_total_billed', 10, 2)->nullable()->after('provider_tracking')->comment('Total facturado al cliente final');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn([
                'provider_cost',
                'provider_weight',
                'provider_length',
                'provider_width',
                'provider_height',
                'provider_tracking',
                'client_total_billed'
            ]);
        });
    }
};
