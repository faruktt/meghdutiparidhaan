<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('branch_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branches')->cascadeOnDelete();
            $table->enum('type', ['income', 'expense'])->default('expense');
            $table->string('category')->default('General');
            $table->string('title');
            $table->decimal('amount', 12, 2);
            $table->date('transaction_date');
            $table->string('payment_method')->default('cash'); // cash, bank, bkash, nagad, card
            $table->string('reference_no')->nullable(); // voucher or receipt number
            $table->string('receipt_file')->nullable();
            $table->text('note')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('admins')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('branch_transactions');
    }
};
