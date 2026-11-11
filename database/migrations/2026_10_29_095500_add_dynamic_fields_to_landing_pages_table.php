<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('landing_pages', function (Blueprint $table) {
            $table->string('urgency_text')->nullable()->after('title');
            $table->longText('details')->nullable()->after('description');
            $table->string('feature_badge_1')->nullable()->after('banner_image');
            $table->string('feature_badge_2')->nullable()->after('feature_badge_1');
        });
    }

    public function down(): void
    {
        Schema::table('landing_pages', function (Blueprint $table) {
            $table->dropColumn(['urgency_text', 'details', 'feature_badge_1', 'feature_badge_2']);
        });
    }
};
