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
        Schema::table('customers', function (Blueprint $table) {
            $table->string('box_number_air')->nullable()->after('box_number');
            $table->string('box_number_maritime')->nullable()->after('box_number_air');
        });

        Schema::table('warehouses', function (Blueprint $table) {
            $table->string('service_type')->default('both')->after('is_active'); // air, maritime, both
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['box_number_air', 'box_number_maritime']);
        });

        Schema::table('warehouses', function (Blueprint $table) {
            $table->dropColumn('service_type');
        });
    }
};
