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
        Schema::table('plans', function (Blueprint $table) {
            $table->string('slug')->unique()->nullable()->after('name');
            $table->text('description')->nullable()->after('slug');
            $table->decimal('price_annual', 10, 2)->nullable()->after('price');
            $table->decimal('price_5year', 10, 2)->nullable()->after('price_annual');
            $table->json('features_json')->nullable()->after('price_5year');
            $table->boolean('is_active')->default(true)->after('features_json');
            $table->boolean('is_featured')->default(false)->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn(['slug', 'description', 'price_annual', 'price_5year', 'features_json', 'is_active', 'is_featured']);
        });
    }
};
