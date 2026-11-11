<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'courier_name')) {
                $table->string('courier_name')->nullable()->after('status');
            }
            if (!Schema::hasColumn('orders', 'consignment_id')) {
                $table->string('consignment_id')->nullable()->after('courier_name');
            }
            if (!Schema::hasColumn('orders', 'tracking_code')) {
                $table->string('tracking_code')->nullable()->after('consignment_id');
            }
            if (!Schema::hasColumn('orders', 'courier_status')) {
                $table->string('courier_status')->nullable()->after('tracking_code');
            }
            if (!Schema::hasColumn('orders', 'courier_response')) {
                $table->text('courier_response')->nullable()->after('courier_status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['courier_name', 'consignment_id', 'tracking_code', 'courier_status', 'courier_response']);
        });
    }
};

