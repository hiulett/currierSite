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
        Schema::table('loyalty_levels', function (Blueprint $table) {
            $table->integer('free_pounds')->default(0)->after('multiplier')
                ->comment('Libras gratis otorgadas al alcanzar el nivel');
            $table->boolean('is_active')->default(true)->after('color');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loyalty_levels', function (Blueprint $table) {
            $table->dropColumn(['free_pounds', 'is_active']);
        });
    }
};
