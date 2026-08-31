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
        Schema::create('licenses', function (Blueprint $table) {
            $table->id();
            $table->string('license_id')->unique();
            $table->date('license_expiry');
            $table->enum('license_status', ['active', 'inactive', 'expired', 'suspended'])->default('active');
            $table->unsignedInteger('max_active_user')->default(0);
            $table->unsignedInteger('max_attachment_size_mb')->default(0);
            $table->string('host')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('licenses');
    }
};
