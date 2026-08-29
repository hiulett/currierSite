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
        Schema::table('loyalty_points_history', function (Blueprint $table) {
            $table->unique(['customer_id', 'type', 'reference_id', 'reference_type'], 'lph_unique_accrual');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loyalty_points_history', function (Blueprint $table) {
            $table->dropUnique('lph_unique_accrual');
        });
    }
};
