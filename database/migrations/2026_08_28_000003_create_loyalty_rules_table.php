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
        Schema::create('loyalty_rules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->decimal('points_per_pound', 6, 2)->default(3.00);
            $table->boolean('enabled')->default(true);
            $table->string('apply_on')->default('invoice')
                ->comment('invoice | delivery');
            $table->json('eligible_rate_types')->nullable()
                ->comment('Tarifas elegibles para acumular, ej. ["regular"]');
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->unique('tenant_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loyalty_rules');
    }
};
