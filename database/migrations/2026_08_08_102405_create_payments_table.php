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
        Schema::create('payments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('booking_id')
                  ->constrained('bookings')->restrictOnDelete();
            $table->foreignUuid('invoice_id')
                  ->constrained('invoices')->nullable();
            $table->decimal('amount', 10, 2);
            $table->enum('type', ['deposit', 'full_payment', 'refund']);
            $table->enum('method', ['cash', 'card', 'bank_transfer']);
            $table->string('stripe_payment_intent_id')->nullable();
            $table->timestamp('paid_at')->useCurrent();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
