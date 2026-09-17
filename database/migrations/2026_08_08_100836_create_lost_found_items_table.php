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
        Schema::create('lost_found_item', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('room_id')
                  ->constrained('rooms')->cascadeOnDelete();
            $table->foreignUuid('found_by')
                 ->constrained('users')->restrictOnDelete();
            $table->text('item_description');
            $table->string('storage_location')->nullable();
            $table->enum('status', ['stored', 'returned', 'disposed'])
                  ->default('stored');
            $table->timestamp('found_at')->useCurrent();
            $table->timestamp('returned_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lost_found_item');
    }
};
