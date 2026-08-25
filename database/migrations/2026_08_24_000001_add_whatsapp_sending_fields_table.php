<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->string('client_phone')->nullable()->after('client_email');
            $table->timestamp('whatsapp_sent_at')->nullable()->after('status');
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->timestamp('whatsapp_sent_at')->nullable()->after('email_sent_at');
        });
    }

    public function down(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->dropColumn(['client_phone', 'whatsapp_sent_at']);
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('whatsapp_sent_at');
        });
    }
};
