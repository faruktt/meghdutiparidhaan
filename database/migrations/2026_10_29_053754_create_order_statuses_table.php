<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('color', 20)->default('#4B5563'); // Badge text/icon color
            $table->string('bg_color', 20)->default('#F3F4F6'); // Badge pill background
            $table->string('row_bg', 20)->default('#FFFFFF'); // Table row background tint
            $table->integer('sort_order')->default(0);
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Insert defaults
        $defaults = [
            ['name' => 'Pending', 'slug' => 'pending', 'color' => '#D97706', 'bg_color' => '#FEF3C7', 'row_bg' => '#FFFDF0', 'sort_order' => 1, 'is_default' => true, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Confirmed', 'slug' => 'confirmed', 'color' => '#2563EB', 'bg_color' => '#DBEAFE', 'row_bg' => '#F8FAFC', 'sort_order' => 2, 'is_default' => false, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Processing', 'slug' => 'processing', 'color' => '#7C3AED', 'bg_color' => '#EDE9FE', 'row_bg' => '#FAF5FF', 'sort_order' => 3, 'is_default' => false, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Shipped', 'slug' => 'shipped', 'color' => '#0284C7', 'bg_color' => '#E0F2FE', 'row_bg' => '#F0F9FF', 'sort_order' => 4, 'is_default' => false, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Delivered', 'slug' => 'delivered', 'color' => '#16A34A', 'bg_color' => '#DCFCE7', 'row_bg' => '#F0FDF4', 'sort_order' => 5, 'is_default' => false, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Cancelled', 'slug' => 'cancelled', 'color' => '#DC2626', 'bg_color' => '#FEE2E2', 'row_bg' => '#FEF2F2', 'sort_order' => 6, 'is_default' => false, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Returned', 'slug' => 'returned', 'color' => '#C2410C', 'bg_color' => '#FFEDD5', 'row_bg' => '#FFF7ED', 'sort_order' => 7, 'is_default' => false, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('order_statuses')->insert($defaults);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_statuses');
    }
};
