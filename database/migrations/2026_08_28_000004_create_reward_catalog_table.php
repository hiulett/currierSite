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
        Schema::create('reward_catalog', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->string('name');
            $table->string('type')->default('free_pounds')
                ->comment('free_pounds | physical');
            $table->integer('points_cost');
            $table->decimal('value', 8, 2)->default(0)
                ->comment('Libras gratis o cantidad del artículo');
            $table->string('description')->nullable();
            $table->string('image_url')->nullable();
            $table->string('icon')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('stock')->nullable()
                ->comment('null = ilimitado');
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reward_catalog');
    }
};
