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
        Schema::create('invoices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('booking_id')
                  ->constrained('bookings')->cascadeOnDelete();
            $table->decimal('room_charge', 10, 2);
            $table->decimal('services_charge', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            $table->timestamp('issued_at')->useCurrent();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
