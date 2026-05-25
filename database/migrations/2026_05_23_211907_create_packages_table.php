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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('warehouse_id')->constrained()->onDelete('cascade');
            $table->string('tracking_number')->index();
            $table->string('internal_tracking')->unique()->nullable();
            $table->text('description')->nullable();
            $table->decimal('weight', 8, 2)->default(0.00);
            $table->decimal('length', 8, 2)->default(0.00);
            $table->decimal('width', 8, 2)->default(0.00);
            $table->decimal('height', 8, 2)->default(0.00);
            $table->decimal('declared_value', 10, 2)->default(0.00);
            $table->string('status')->default('received'); // prealert, received, in_transit, delivered, etc.
            $table->json('images_json')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
