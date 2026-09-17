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
        Schema::create('room_type_has_amenities', function (Blueprint $table) {
            $table->foreignUuid('room_type_id')
                  ->constrained('rooms_types')
                  ->cascadeOnDelete();
            $table->foreignUuid('amenity_id')
                  ->constrained('amenities')
                  ->cascadeOnDelete();
            $table->primary(['room_type_id', 'amenity_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_type_has_amenities');
    }
};
