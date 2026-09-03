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
            // Renamed to match the client's report-usage query params
            // exactly: total_active_user -> active_users (enabled user
            // count), current_users -> total_users (enabled + disabled).
            $table->renameColumn('total_active_user', 'active_users');
            $table->renameColumn('current_users', 'total_users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('licenses', function (Blueprint $table) {
            $table->renameColumn('active_users', 'total_active_user');
            $table->renameColumn('total_users', 'current_users');
        });
    }
};
