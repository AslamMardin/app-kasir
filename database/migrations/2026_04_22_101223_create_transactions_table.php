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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique()->index();
            $table->foreignId('shift_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('member_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('subtotal', 15, 2);
            $table->decimal('discount_total', 15, 2)->default(0);
            $table->decimal('tax_total', 15, 2)->default(0);
            $table->decimal('grand_total', 15, 2);
            $table->enum('payment_method', ['cash', 'qris', 'edc', 'split'])->default('cash');
            $table->decimal('payment_amount', 15, 2);
            $table->decimal('change_amount', 15, 2)->default(0);
            $table->enum('status', ['completed', 'voided', 'pending'])->default('completed');
            $table->foreignId('voided_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('void_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
