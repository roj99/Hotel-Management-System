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
        Schema::create('room_services', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('booking_id')
                  ->constrained('bookings')->cascadeOnDelete();
            $table->foreignUuid('handled_by')->nullable()
                  ->constrained('users')->nullOnDelete();
            $table->string('item_description');
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('price', 8, 2);
            $table->enum('status', ['pending', 'preparing', 'delivered', 'cancelled'])
                  ->default('pending');
            $table->timestamp('ordered_at')->useCurrent();
            $table->timestamp('delivered_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_services');
    }
};
