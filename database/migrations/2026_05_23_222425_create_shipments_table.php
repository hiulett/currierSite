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
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->string('manifest_number')->unique();
            $table->string('carrier_name')->nullable(); // e.g. Copa Airlines, DHL
            $table->string('master_tracking')->nullable();
            $table->string('status')->default('draft'); // draft, in_transit, arrived, closed
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('estimated_arrival')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::table('packages', function (Blueprint $table) {
            $table->foreignId('shipment_id')->nullable()->constrained()->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropConstrainedForeignId('shipment_id');
        });
        Schema::dropIfExists('shipments');
    }
};
