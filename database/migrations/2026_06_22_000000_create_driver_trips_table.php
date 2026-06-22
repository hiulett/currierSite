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
        Schema::create('driver_trips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->string('driver_name');
            $table->string('company_name');
            $table->text('description')->nullable();
            $table->decimal('outsourcing_cost', 10, 2)->default(0);
            $table->decimal('final_client_price', 10, 2)->default(0);
            $table->decimal('revenue', 10, 2)->default(0);
            $table->string('invoice_number')->nullable();
            $table->string('invoice_status')->default('PENDIENTE');
            $table->string('driver_payment_status')->default('PENDIENTE');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driver_trips');
    }
};
