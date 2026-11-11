<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            // Customer Info
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_email')->nullable();
            $table->text('customer_address');
            $table->string('customer_note')->nullable();
            // Pricing
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('shipping_charge', 10, 2)->default(0);
            $table->decimal('discount_amount', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            // Coupon
            $table->string('coupon_code')->nullable();
            // Shipping
            $table->foreignId('shipping_zone_id')->nullable()->constrained()->nullOnDelete();
            // Payment
            $table->string('payment_method')->default('cod'); // cod, bkash
            $table->string('payment_status')->default('pending'); // pending, paid, failed
            $table->string('bkash_transaction_id')->nullable();
            // Status
            $table->string('status')->default('pending'); // pending, confirmed, processing, shipped, delivered, cancelled, returned
            $table->string('source')->default('website'); // website, pos
            $table->text('admin_note')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('product_variant_id')->nullable()->constrained()->nullOnDelete();
            $table->string('product_name');
            $table->string('variant_info')->nullable(); // "Color: Red, Size: M"
            $table->decimal('price', 10, 2);
            $table->integer('quantity');
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
