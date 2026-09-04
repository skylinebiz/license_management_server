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
        Schema::table('licenses', function (Blueprint $table) {
            // Reported by the licensed application, not set by an admin —
            // see LicenseController::reportUsage().
            $table->unsignedInteger('total_active_user')->default(0)->after('simultaneous_sessions');
            $table->unsignedInteger('current_users')->default(0)->after('total_active_user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('licenses', function (Blueprint $table) {
            $table->dropColumn(['total_active_user', 'current_users']);
        });
    }
};
