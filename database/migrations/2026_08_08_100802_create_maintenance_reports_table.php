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
        Schema::create('maintenance_reports', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('room_id')
                   ->constrained('rooms')->cascadeOnDelete();
            $table->foreignUuid('reported_by')
                  ->constrained('users')->restrictOnDelete();
            $table->text('issue_description');
            $table->enum('severity', ['low', 'medium', 'high', 'critical'])
                  ->default('low');
            $table->enum('status', ['open', 'in_progress', 'resolved'])
                  ->default('open');
            $table->foreignUuid('resolved_by')->nullable()
                  ->constrained('users')->nullOnDelete();
            $table->timestamp('reported_at')->useCurrent();
            $table->timestamp('resolved_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_reports');
    }
};
