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
        Schema::create('booking_actions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('booking_id')
                  ->constrained('bookings')->cascadeOnDelete();
            $table->enum('action_type', ['reviewed', 'cancelled']);
            $table->foreignUuid('performed_by')->nullable()
                  ->constrained('users')->nullOnDelete();
            $table->text('reason')->nullable();
            $table->timestamp('performed_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_actions');
    }
};
