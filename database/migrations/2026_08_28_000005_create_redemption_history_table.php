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
        Schema::create('redemption_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('reward_id')->nullable();
            $table->integer('points_spent');
            $table->string('reward_name');
            $table->decimal('free_pounds_granted', 8, 2)->default(0);
            $table->string('status')->default('completed')
                ->comment('pending | completed | cancelled');
            $table->timestamp('redeemed_at')->nullable();
            $table->unsignedBigInteger('redeemed_by_user_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('reward_id')->references('id')->on('reward_catalog')->onDelete('set null');
            $table->foreign('redeemed_by_user_id')->references('id')->on('users')->onDelete('set null');
            $table->index(['tenant_id', 'customer_id', 'redeemed_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('redemption_history');
    }
};
