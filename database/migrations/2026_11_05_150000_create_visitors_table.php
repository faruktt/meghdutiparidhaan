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
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45)->nullable()->index();
            $table->string('session_id', 100)->nullable()->index();
            $table->text('page_url');
            $table->string('page_title')->nullable();
            $table->string('device_type', 20)->default('Desktop');
            $table->string('browser', 50)->nullable();
            $table->string('platform', 50)->nullable();
            $table->text('referrer')->nullable();
            $table->date('visit_date')->index();
            $table->timestamps();

            $table->index(['visit_date', 'ip_address']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visitors');
    }
};

