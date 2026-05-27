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
        Schema::create('manifests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->string('number')->unique();
            $table->string('carrier_name')->nullable();
            $table->string('carrier_invoice_number')->nullable();
            $table->text('description')->nullable();
            $table->string('status')->default('pending'); // pending, processing, reconciled, closed
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('received_at')->nullable();
            $table->integer('total_items_expected')->default(0);
            $table->integer('total_items_received')->default(0);
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
        });

        Schema::create('manifest_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('manifest_id');
            $table->unsignedBigInteger('tenant_id');
            $table->string('tracking_number')->index();
            $table->unsignedBigInteger('package_id')->nullable(); // Relación con el paquete real si existe
            $table->string('status')->default('expected'); // expected, received, missing, surplus
            $table->timestamp('scanned_at')->nullable();
            $table->text('observation')->nullable();
            $table->timestamps();

            $table->foreign('manifest_id')->references('id')->on('manifests')->onDelete('cascade');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manifest_items');
        Schema::dropIfExists('manifests');
    }
};
