<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Agrega login_url_slug para URLs personalizadas de tenant,
     * y tenant_id a la tabla de sesiones para mejor tracking.
     */
    public function up(): void
    {
        // Columna login_url_slug en tenants
        if (Schema::hasTable('tenants') && !Schema::hasColumn('tenants', 'login_url_slug')) {
            Schema::table('tenants', function (Blueprint $table) {
                $table->string('login_url_slug')->nullable()->unique()->after('subdomain');
            });
        }

        // Columna tenant_id en sesiones para invalidación por tenant
        if (Schema::hasTable('sessions') && !Schema::hasColumn('sessions', 'tenant_id')) {
            Schema::table('sessions', function (Blueprint $table) {
                $table->unsignedBigInteger('tenant_id')->nullable()->index()->after('user_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn('login_url_slug');
        });

        if (Schema::hasTable('sessions') && Schema::hasColumn('sessions', 'tenant_id')) {
            Schema::table('sessions', function (Blueprint $table) {
                $table->dropColumn('tenant_id');
            });
        }
    }
};
