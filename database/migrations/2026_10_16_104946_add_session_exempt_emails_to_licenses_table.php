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
            // JSON array of user emails that the licensed app should NOT hold
            // to this license's simultaneous_sessions limit. Managed from the
            // admin UI; returned by the API as `session_exempt_emails`.
            $table->json('session_exempt_emails')->nullable()->after('simultaneous_sessions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('licenses', function (Blueprint $table) {
            $table->dropColumn('session_exempt_emails');
        });
    }
};
