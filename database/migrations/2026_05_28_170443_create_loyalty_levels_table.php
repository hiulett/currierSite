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
        Schema::create('loyalty_levels', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->string('name');
            $table->integer('min_points')->default(0);
            $table->integer('max_points')->nullable();
            $table->decimal('multiplier', 5, 2)->default(1.00); // Para ganar más puntos por libra
            $table->string('icon')->nullable();
            $table->string('color')->default('#000000');
            $table->integer('priority')->default(0);
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
        });

        // Añadir columna de nivel a la tabla de clientes si no existe
        Schema::table('customers', function (Blueprint $table) {
            $table->unsignedBigInteger('loyalty_level_id')->nullable()->after('points');
            $table->foreign('loyalty_level_id')->references('id')->on('loyalty_levels')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign(['loyalty_level_id']);
            $table->dropColumn('loyalty_level_id');
        });
        Schema::dropIfExists('loyalty_levels');
    }
};
