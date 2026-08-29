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
            $table->string('rate_type')->default('regular')->after('points')
                ->comment('regular | reseller | special');
            $table->boolean('is_loyalty_eligible')->default(true)->after('rate_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['rate_type', 'is_loyalty_eligible']);
        });
    }
};
