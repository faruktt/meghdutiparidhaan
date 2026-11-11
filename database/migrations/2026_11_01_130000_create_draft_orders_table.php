<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('draft_orders', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable()->index();
            $table->text('customer_address')->nullable();
            $table->unsignedBigInteger('shipping_zone_id')->nullable();
            $table->json('cart_data')->nullable();
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('shipping_charge', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('status')->default('pending')->index(); // pending, converted, cancelled
            $table->unsignedBigInteger('order_id')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('draft_orders');
    }
};

